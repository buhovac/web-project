<?php
//require_once dirname(__DIR__) . '/config/constants.php';
?>
</main>

<footer role="contentinfo">
  <nav aria-label="Liens secondaires et légaux">
    <ul>
      <li><a href="#">Politique de Confidentialité</a></li>
      <li><a href="#">Conditions d'Utilisation</a></li>
    </ul>
  </nav>
  <p>&copy; <span id="current-year">2026</span> Projet Web — Marko Buhovac. Tous droits réservés.
  </p>
  <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>
</footer>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        easing: "ease-out",
        offset: 120,
        delay: 0,
        once: true,
    });
</script>
<script defer src="scripts/app.js"></script>
</body>
</html>