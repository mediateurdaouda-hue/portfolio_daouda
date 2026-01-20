<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="entete.css">

    

    <!-- Manifest PWA -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#ff6600">

<!-- Icônes -->
<link rel="apple-touch-icon" href="/icons/icon-192.png">

<!-- Service Worker -->
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
      .then(() => console.log('✅ Service Worker actif'))
      .catch(error => console.error('❌ SW error:', error));
  }
</script>




</head>
<body>
    
    <div class="menu">

    <ul class="menu">
        <li><a href="index.php">acceuil</a></li>
        <li><a href="a propo.php">à propos</a></li>
        <li class="dropdown">
            <a href="#">Type</a>
                <div class="dropdown-content">
                    <a href="appartement.html">Appartement</a>
                    <a href="immeuble.html">Immeuble</a>
                    <a href="Centre_commerciale.html">commerce</a>
                    <a href="villa.html">Villa</a>
                    <a href="magasin.html">Magasin</a>
                </div>
        </li>
        <li class="dropdown">
            <a href="#">Catégorie</a>
            <div class="dropdown-content">
                <a href="residence.html">Résidence</a>
                <a href="bureau.html">Bureau</a>
                <a href="commerce.html">Commerce</a>
                <a href="agriculture.html">Agriculture</a>
                <a href="industrie.html">Industrie</a>
            </div>
        </li>
        <li><a href="favori.html">Favoris</a></li>
    </ul>
</div>




</body>
</html>