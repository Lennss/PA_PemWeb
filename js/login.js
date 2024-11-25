/**
 * Toggles password visibility and updates the icon.
 * @param {string} fieldId - The ID of the password input field.
 * @param {HTMLElement} toggleIcon - The element clicked for toggling visibility.
 */
function togglePassword(fieldId, toggleIcon) {
  const passwordField = document.getElementById(fieldId);
  const isPasswordVisible = passwordField.type === "password";

  passwordField.type = isPasswordVisible ? "text" : "password";

  const iconUse = toggleIcon.querySelector("use");
  if (isPasswordVisible) {
    iconUse.setAttribute("xlink:href", "#mdi--eye-off-outline");
  } else {
    iconUse.setAttribute("xlink:href", "#mdi--eye-outline");
  }
}
