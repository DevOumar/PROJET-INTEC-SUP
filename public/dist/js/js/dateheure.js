function Timer() {
    // Créer un objet Date
    var dt = new Date();
    // Formater la date
    var dateLocale = dt.toLocaleString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    // Formater l'heure
    var hours = dt.getHours().toString().padStart(2, '0');
    var minutes = dt.getMinutes().toString().padStart(2, '0');
    var seconds = dt.getSeconds().toString().padStart(2, '0');
    var formatedTime = hours + ":" + minutes + ":" + seconds;

    // Afficher la date et l'heure
    document.getElementById("p1").innerHTML = 'Date : ' + dateLocale + ", " + formatedTime;
    
    // Appeler Timer() toutes les 900 millisecondes
    setTimeout(Timer, 900);
}

// Lancer la fonction Timer
Timer();
