// ===========================
// SCRIPT POUR GÉRER L'AFFICHAGE DES FORMULAIRES
// ===========================

/**
 * Affiche le formulaire de connexion
 * et cache le formulaire d'inscription
 */
function afficherConnexion() {
  document.getElementById("inscription-form").style.display = "none";
  document.getElementById("connexion-form").style.display = "block";
}

/**
 * Affiche le formulaire d'inscription
 * et cache le formulaire de connexion
 */
function afficherInscription() {
  document.getElementById("connexion-form").style.display = "none";
  document.getElementById("inscription-form").style.display = "block";
}
