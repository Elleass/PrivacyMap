const menuButton = document.querySelector(".menu-button");
const navList = document.querySelector(".nav-links");

if (menuButton && navList) {
  menuButton.addEventListener("click", () => {
    navList.classList.toggle("open");
  });
}

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

document.querySelectorAll("[data-auth-form]").forEach((form) => {
  const submitButton = form.querySelector('button[type="submit"]');

  form.querySelectorAll("input").forEach((input) => {
    input.addEventListener("input", () => {
      clearFieldError(input);
    });

    input.addEventListener("blur", () => {
      validateField(form, input);
    });
  });

  form.addEventListener("submit", (event) => {
    clearFormErrors(form);

    if (!validateAuthForm(form)) {
      event.preventDefault();
      return;
    }

    if (submitButton) {
      setLoading(submitButton);
    }
  });
});

document.querySelectorAll("[data-fetch-complete]").forEach((form) => {
  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const button = form.querySelector('button[type="submit"]');
    const row = form.closest(".recommendation-row");

    if (button) {
      setLoading(button);
    }

    try {
      const response = await fetch(form.action, {
        method: "POST",
        body: new FormData(form),
        headers: {
          Accept: "application/json",
        },
      });

      const payload = await response.json();
      if (!response.ok || !payload.success) {
        throw new Error(payload.message || "Recommendation could not be completed.");
      }

      markRecommendationCompleted(row, form);
    } catch (error) {
      if (button) {
        resetLoading(button);
      }

      showInlineStatus(form, error.message);
    }
  });
});

function validateAuthForm(form) {
  let isValid = true;

  form.querySelectorAll("input:not([type='hidden'])").forEach((input) => {
    if (!validateField(form, input)) {
      isValid = false;
    }
  });

  const password = form.querySelector("input[name='password']");
  const repeatedPassword = form.querySelector("input[name='password2']");

  if (password && repeatedPassword && password.value !== repeatedPassword.value) {
    showFieldError(repeatedPassword, "Passwords must match.");
    isValid = false;
  }

  return isValid;
}

function validateField(form, input) {
  const value = input.value.trim();
  if (input.required && value === "") {
    showFieldError(input, "This field is required.");
    return false;
  }

  if (input.name === "email" && value !== "" && !emailPattern.test(value)) {
    showFieldError(input, "Enter a valid email address.");
    return false;
  }

  if (input.name === "name" && value.length > 100) {
    showFieldError(input, "Name can have at most 100 characters.");
    return false;
  }

  if (input.name === "password" && value !== "") {
    if (value.length < 8) {
      showFieldError(input, "Password must have at least 8 characters.");
      return false;
    }
  }

  clearFieldError(input);
  return true;
}

function showFieldError(input, message) {
  const label = input.closest("label");
  if (!label) {
    return;
  }

  clearFieldError(input);
  input.classList.add("invalid");

  const error = document.createElement("span");
  error.className = "field-error";
  error.textContent = message;
  label.appendChild(error);
}

function clearFieldError(input) {
  const label = input.closest("label");
  input.classList.remove("invalid");

  if (label) {
    label.querySelector(".field-error")?.remove();
  }
}

function clearFormErrors(form) {
  form.querySelectorAll("input").forEach(clearFieldError);
}

function setLoading(button) {
  const loadingText = button.dataset.loadingText || "Loading...";
  button.dataset.originalText = button.textContent;
  button.disabled = true;
  button.classList.add("is-loading");
  button.textContent = loadingText;
}

function resetLoading(button) {
  button.disabled = false;
  button.classList.remove("is-loading");
  button.textContent = button.dataset.originalText || "Submit";
}

function markRecommendationCompleted(row, form) {
  if (!row) {
    return;
  }

  row.classList.add("completed");
  form.replaceWith(createCompletedLabel());
}

function createCompletedLabel() {
  const label = document.createElement("span");
  label.className = "completed-label";
  label.textContent = "Completed";
  return label;
}

function showInlineStatus(form, message) {
  form.querySelector(".inline-status")?.remove();

  const status = document.createElement("p");
  status.className = "inline-status";
  status.textContent = message;
  form.appendChild(status);
}
