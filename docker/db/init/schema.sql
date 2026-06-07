DROP VIEW IF EXISTS user_service_summary CASCADE;
DROP TABLE IF EXISTS audit_logs CASCADE;
DROP TABLE IF EXISTS recommendations CASCADE;
DROP TABLE IF EXISTS service_data_types CASCADE;
DROP TABLE IF EXISTS user_services CASCADE;
DROP TABLE IF EXISTS services CASCADE;
DROP TABLE IF EXISTS data_types CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS roles CASCADE;
DROP FUNCTION IF EXISTS set_updated_at() CASCADE;
DROP FUNCTION IF EXISTS privacy_risk_level(INTEGER) CASCADE;
DROP FUNCTION IF EXISTS calculate_privacy_health(INTEGER) CASCADE;

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    role_id INTEGER NOT NULL REFERENCES roles(id),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    name VARCHAR(150) NOT NULL,
    website_url VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_services (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    service_id INTEGER REFERENCES services(id) ON DELETE SET NULL,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    custom_name VARCHAR(150) NOT NULL,
    website_url VARCHAR(255),
    notes TEXT,
    risk_score INTEGER DEFAULT 0,
    risk_level VARCHAR(20) DEFAULT 'low' CHECK (risk_level IN ('low', 'medium', 'high')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE data_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    sensitivity_level INTEGER NOT NULL DEFAULT 1 CHECK (sensitivity_level BETWEEN 1 AND 5)
);

CREATE TABLE service_data_types (
    id SERIAL PRIMARY KEY,
    user_service_id INTEGER NOT NULL REFERENCES user_services(id) ON DELETE CASCADE,
    data_type_id INTEGER NOT NULL REFERENCES data_types(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_service_id, data_type_id)
);

CREATE TABLE recommendations (
    id SERIAL PRIMARY KEY,
    user_service_id INTEGER NOT NULL REFERENCES user_services(id) ON DELETE CASCADE,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    priority VARCHAR(20) DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100),
    entity_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_user_services_user_id ON user_services(user_id);
CREATE INDEX idx_service_data_types_user_service_id ON service_data_types(user_service_id);
CREATE INDEX idx_recommendations_user_service_id ON recommendations(user_service_id);
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);

CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_users_updated_at
BEFORE UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER trg_services_updated_at
BEFORE UPDATE ON services
FOR EACH ROW
EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER trg_user_services_updated_at
BEFORE UPDATE ON user_services
FOR EACH ROW
EXECUTE FUNCTION set_updated_at();

CREATE OR REPLACE FUNCTION privacy_risk_level(score INTEGER)
RETURNS VARCHAR(20) AS $$
BEGIN
    IF score >= 9 THEN
        RETURN 'high';
    ELSIF score >= 4 THEN
        RETURN 'medium';
    END IF;

    RETURN 'low';
END;
$$ LANGUAGE plpgsql IMMUTABLE;

CREATE OR REPLACE FUNCTION calculate_privacy_health(target_user_id INTEGER)
RETURNS INTEGER AS $$
DECLARE
    total_services INTEGER;
    data_points INTEGER;
    high_risk INTEGER;
BEGIN
    SELECT COUNT(*) INTO total_services
    FROM user_services
    WHERE user_id = target_user_id;

    SELECT COUNT(*) INTO data_points
    FROM user_services us
    JOIN service_data_types sdt ON sdt.user_service_id = us.id
    WHERE us.user_id = target_user_id;

    SELECT COUNT(*) INTO high_risk
    FROM user_services
    WHERE user_id = target_user_id AND risk_level = 'high';

    RETURN GREATEST(0, 100 - (high_risk * 12) - GREATEST(0, data_points - total_services) * 2);
END;
$$ LANGUAGE plpgsql STABLE;

CREATE VIEW user_service_summary AS
SELECT
    us.id,
    us.user_id,
    COALESCE(us.custom_name, s.name) AS display_name,
    c.name AS category_name,
    us.risk_score,
    us.risk_level,
    COUNT(sdt.id) AS data_points,
    us.created_at,
    us.updated_at
FROM user_services us
LEFT JOIN services s ON s.id = us.service_id
LEFT JOIN categories c ON c.id = us.category_id
LEFT JOIN service_data_types sdt ON sdt.user_service_id = us.id
GROUP BY us.id, s.name, c.name;
