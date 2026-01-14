<?php
// pay_fake.php (si tu veux une page "paiement fake" séparée)
//(optionnel mais tu l’as → redirection simple vers checkout.php)
// Ici on redirige juste vers checkout
header("Location: checkout.php");
exit;
