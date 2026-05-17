INSERT INTO roles (name) VALUES ('user'), ('admin');

INSERT INTO categories (name, description) VALUES
('Social Media', 'Social networks and community platforms'),
('Finance', 'Banking, payment and money management services'),
('Shopping', 'Stores, marketplaces and delivery services'),
('Streaming', 'Music and video subscriptions'),
('Productivity', 'Work, documents and collaboration tools'),
('Health', 'Health, fitness and wellness services'),
('Travel', 'Travel, maps and booking services'),
('Communication', 'Mail, chat and messaging services');

INSERT INTO data_types (name, description, sensitivity_level) VALUES
('Email Address', 'Primary contact email', 1),
('Phone Number', 'Personal phone number', 2),
('Home Address', 'Residential or shipping address', 3),
('Payment Details', 'Cards, billing data or payment tokens', 4),
('Financial Records', 'Balances, transactions or financial history', 5),
('Behavioral Analytics', 'Activity, tracking and personalization data', 3),
('Location History', 'GPS, travel or check-in history', 4),
('Contact Lists', 'Imported or synced contacts', 3),
('Biometric Data', 'Face, fingerprint or voice identifiers', 5),
('Identity Documents', 'Government IDs or verification documents', 5),
('Purchase History', 'Orders, baskets or shopping behavior', 2),
('Health Data', 'Medical, wellness or fitness data', 5);

INSERT INTO services (category_id, name, website_url, description) VALUES
((SELECT id FROM categories WHERE name = 'Social Media'), 'Meta', 'https://meta.com', 'Social media and advertising ecosystem'),
((SELECT id FROM categories WHERE name = 'Communication'), 'Google', 'https://google.com', 'Search, email, maps and account services'),
((SELECT id FROM categories WHERE name = 'Shopping'), 'Amazon', 'https://amazon.com', 'Marketplace and subscription services'),
((SELECT id FROM categories WHERE name = 'Streaming'), 'Netflix', 'https://netflix.com', 'Video streaming service'),
((SELECT id FROM categories WHERE name = 'Streaming'), 'Spotify', 'https://spotify.com', 'Music streaming service'),
((SELECT id FROM categories WHERE name = 'Finance'), 'PayPal', 'https://paypal.com', 'Online payment service'),
((SELECT id FROM categories WHERE name = 'Productivity'), 'LinkedIn', 'https://linkedin.com', 'Professional networking platform');

-- Demo password for both accounts: password
INSERT INTO users (role_id, name, email, password_hash) VALUES
((SELECT id FROM roles WHERE name = 'admin'), 'Demo Admin', 'admin@example.com', '$2y$10$8zpZ52VFyZ/JlxO6oiJ4GeH7m0rYrAdmE4ELz4co4OCuDSUk2AJS.'),
((SELECT id FROM roles WHERE name = 'user'), 'Demo User', 'user@example.com', '$2y$10$8zpZ52VFyZ/JlxO6oiJ4GeH7m0rYrAdmE4ELz4co4OCuDSUk2AJS.');

INSERT INTO user_services (user_id, service_id, category_id, custom_name, website_url, notes, risk_score, risk_level) VALUES
((SELECT id FROM users WHERE email = 'user@example.com'), (SELECT id FROM services WHERE name = 'Meta'), (SELECT id FROM categories WHERE name = 'Social Media'), 'Meta', 'https://meta.com', 'Review ad personalization and app permissions.', 9, 'high'),
((SELECT id FROM users WHERE email = 'user@example.com'), (SELECT id FROM services WHERE name = 'Amazon'), (SELECT id FROM categories WHERE name = 'Shopping'), 'Amazon', 'https://amazon.com', 'Contains purchases and shipping information.', 10, 'high'),
((SELECT id FROM users WHERE email = 'user@example.com'), (SELECT id FROM services WHERE name = 'Netflix'), (SELECT id FROM categories WHERE name = 'Streaming'), 'Netflix', 'https://netflix.com', 'Low sensitivity streaming profile.', 3, 'low'),
((SELECT id FROM users WHERE email = 'user@example.com'), (SELECT id FROM services WHERE name = 'PayPal'), (SELECT id FROM categories WHERE name = 'Finance'), 'PayPal', 'https://paypal.com', 'Payment methods should be reviewed regularly.', 10, 'high');

INSERT INTO service_data_types (user_service_id, data_type_id)
SELECT us.id, dt.id FROM user_services us, data_types dt
WHERE us.custom_name = 'Meta' AND dt.name IN ('Email Address', 'Phone Number', 'Home Address', 'Behavioral Analytics');

INSERT INTO service_data_types (user_service_id, data_type_id)
SELECT us.id, dt.id FROM user_services us, data_types dt
WHERE us.custom_name = 'Amazon' AND dt.name IN ('Email Address', 'Phone Number', 'Home Address', 'Payment Details', 'Purchase History');

INSERT INTO service_data_types (user_service_id, data_type_id)
SELECT us.id, dt.id FROM user_services us, data_types dt
WHERE us.custom_name = 'Netflix' AND dt.name IN ('Email Address', 'Payment Details');

INSERT INTO service_data_types (user_service_id, data_type_id)
SELECT us.id, dt.id FROM user_services us, data_types dt
WHERE us.custom_name = 'PayPal' AND dt.name IN ('Email Address', 'Phone Number', 'Payment Details', 'Financial Records');

INSERT INTO recommendations (user_service_id, title, description, priority)
SELECT id, 'Enable two-factor authentication', 'Local checklist item to reduce this service data exposure.', 'high'
FROM user_services WHERE risk_level = 'high';

INSERT INTO recommendations (user_service_id, title, description, priority)
SELECT id, 'Review saved payment methods', 'Remove unused payment methods where possible.', 'high'
FROM user_services WHERE custom_name IN ('Amazon', 'PayPal', 'Netflix');

INSERT INTO audit_logs (user_id, action, entity_type, entity_id)
SELECT id, 'seeded_demo_data', 'user', id FROM users WHERE email = 'user@example.com';
