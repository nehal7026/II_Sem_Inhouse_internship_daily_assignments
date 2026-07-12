/* ============================================
   Password Strength Checker — script.js
   Pure vanilla JS. No frameworks.
   ============================================ */

// A short list of extremely common passwords. Not exhaustive —
// just enough to demonstrate the concept for a student project.
const COMMON_PASSWORDS = [
  "123456", "password", "123456789", "12345678", "12345",
  "qwerty", "abc123", "password1", "111111", "iloveyou",
  "admin", "letmein", "welcome", "monkey", "dragon"
];

/**
 * Evaluate a password and return a score (0-5) plus the criteria
 * that were met / missed.
 */
function evaluatePassword(password) {
  const criteria = {
    length8:   password.length >= 8,
    length12:  password.length >= 12,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number:    /[0-9]/.test(password),
    symbol:    /[^A-Za-z0-9]/.test(password),
    notCommon: !COMMON_PASSWORDS.includes(password.toLowerCase())
  };

  let score = 0;
  if (criteria.length8) score++;
  if (criteria.length12) score++;
  if (criteria.uppercase && criteria.lowercase) score++;
  if (criteria.number) score++;
  if (criteria.symbol) score++;
  if (!criteria.notCommon) score = 0; // common password overrides everything

  if (password.length === 0) score = -1; // empty state

  return { score, criteria };
}

function scoreLabel(score) {
  switch (true) {
    case score <= 0: return { text: "Very Weak", cls: "very-weak" };
    case score === 1: return { text: "Weak", cls: "weak" };
    case score === 2: return { text: "Medium", cls: "medium" };
    case score === 3:
    case score === 4: return { text: "Strong", cls: "strong" };
    default: return { text: "Very Strong", cls: "very-strong" };
  }
}

/**
 * Wire up a password input to a strength meter + criteria list.
 * Call once per page for each checker instance.
 */
function initStrengthChecker({ inputId, meterId, labelId, listId }) {
  const input = document.getElementById(inputId);
  const meter = document.getElementById(meterId);
  const label = document.getElementById(labelId);
  const list  = document.getElementById(listId);

  if (!input) return;

  function render() {
    const password = input.value;
    const { score, criteria } = evaluatePassword(password);
    const { text, cls } = scoreLabel(score);

    // Update bars (5 segments total)
    if (meter) {
      const bars = meter.querySelectorAll(".bar");
      bars.forEach((bar, i) => {
        bar.className = "bar";
        if (i < Math.max(score, 0)) {
          let tier = 0;
          if (score >= 4) tier = 2;
          else if (score >= 2) tier = 1;
          bar.classList.add("filled-" + tier);
        }
      });
    }

    if (label) {
      label.textContent = password.length ? text : "Enter a password";
      label.className = "strength-label " + (password.length ? cls : "");
    }

    if (list) {
      const items = {
        length8: "At least 8 characters",
        uppercase_lowercase: "Upper and lowercase letters",
        number: "Contains a number",
        symbol: "Contains a symbol (!@#$...)",
        notCommon: "Not a commonly used password"
      };
      list.innerHTML = "";
      const hasInput = password.length > 0;
      const rows = [
        [hasInput && criteria.length8, items.length8],
        [hasInput && criteria.uppercase && criteria.lowercase, items.uppercase_lowercase],
        [hasInput && criteria.number, items.number],
        [hasInput && criteria.symbol, items.symbol],
        [hasInput && criteria.notCommon, items.notCommon]
      ];
      rows.forEach(([met, text]) => {
        const li = document.createElement("li");
        li.className = met ? "met" : "";
        li.innerHTML = `<span class="icon">${met ? "✓" : "✗"}</span> ${text}`;
        list.appendChild(li);
      });
    }

    return { score, label: text };
  }

  input.addEventListener("input", render);
  render();

  return { render, evaluate: () => evaluatePassword(input.value) };
}

/**
 * Toggle a password field between hidden / visible text.
 * Expects a button with data-target="<input id>".
 */
document.addEventListener("click", function (e) {
  const btn = e.target.closest("[data-toggle-password]");
  if (!btn) return;
  const targetId = btn.getAttribute("data-toggle-password");
  const field = document.getElementById(targetId);
  if (!field) return;
  field.type = field.type === "password" ? "text" : "password";
  btn.textContent = field.type === "password" ? "Show" : "Hide";
});

/**
 * Simple client-side confirm for delete actions.
 */
document.addEventListener("submit", function (e) {
  const form = e.target.closest("form[data-confirm]");
  if (!form) return;
  const msg = form.getAttribute("data-confirm");
  if (!confirm(msg)) {
    e.preventDefault();
  }
});