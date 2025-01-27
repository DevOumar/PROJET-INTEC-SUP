
// Définition des constantes et variables globales
const ACCOUNT_TYPES = [
  { type: "ADMINISTRATEUR", text: "Administrateur" },
  { type: "ETUDIANT", text: "Etudiant" },
  { type: "PROFESSEUR", text: "Professeur" },
  { type: "INVITE", text: "Invite" },
];
let selectedAccountIndex = 0;

// Initialisation de l'interface utilisateur
$(document).ready(function () {
  const previousAccountBtn = document.querySelector("#previous-account-type");
  const nextAccountBtn = document.querySelector("#next-account-type");
  const accountTypeInput = document.querySelector("input#account-type");
  const accountTypeDisplayer = document.querySelector("#account-type-displayer");

  init();

  // Gestion des événements
  previousAccountBtn.addEventListener("click", previousAccount);
  nextAccountBtn.addEventListener("click", nextAccount);

  function init() {
    accountTypeDisplayer.textContent = ACCOUNT_TYPES[selectedAccountIndex].text;
    accountTypeInput.value = ACCOUNT_TYPES[selectedAccountIndex].type;
  }

  function previousAccount(evt) {
    evt.preventDefault();
    selectedAccountIndex = (selectedAccountIndex === 0) ? ACCOUNT_TYPES.length - 1 : selectedAccountIndex - 1;
    updateAccountType();
  }

  function nextAccount(evt) {
    evt.preventDefault();
    selectedAccountIndex = (selectedAccountIndex === ACCOUNT_TYPES.length - 1) ? 0 : selectedAccountIndex + 1;
    updateAccountType();
  }

  function updateAccountType() {
    accountTypeDisplayer.textContent = ACCOUNT_TYPES[selectedAccountIndex].text;
    accountTypeInput.value = ACCOUNT_TYPES[selectedAccountIndex].type;
  }
});



