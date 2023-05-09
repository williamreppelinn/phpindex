<?php


// Connexion à la base de données
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la soumission du formulaire d'inscription
if (isset($_POST["register"])) {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

// Vérification de l'existence de l'utilisateur
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $error_msg = "Ce nom d'utilisateur est déjà utilisé.";
} else {
  // Insertion de l'utilisateur dans la base de données
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
  if ($conn->query($sql) === TRUE) {
    $success_msg = "Inscription réussie.";
  } else {
    $error_msg = "Erreur : " . $sql . "<br>" . $conn->error;
    }
  }
}
// Récupération de l'identifiant du post à supprimer
$id = $_POST['id'];

// Requête SQL pour supprimer le post correspondant à l'identifiant
$sql = "DELETE FROM posts WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo "Le post a été supprimé avec succès.";
} else {
    echo "Erreur : " . mysqli_error($conn);
}
}


// Vérification de la soumission du formulaire d'envoi de post
if (isset($_POST["send_post"])) {
  $user_id = $_POST["user_id"];
  $content = $_POST["content"];

  // Insertion du post dans la base de données
  $sql = "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')";
  if ($conn->query($sql) === TRUE) {
    $success_msg = "Post envoyé avec succès.";
  } else {
    $error_msg = "Erreur : " . $sql . "<br>" . $conn->error;
  }
}

// Récupération de tous les posts de la base de données
$sql = "SELECT posts.*, users.username FROM posts INNER JOIN users ON posts.user_id=users.id ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Réseau social</title>
</head>
<body>
  <?php if (isset($error_msg)) { ?>
    <p><?php echo $error_msg; ?></p>
  <?php } ?>
  <?php if (isset($success_msg)) { ?>
    <p><?php echo $success_msg; ?></p>
  <?php } ?>

  <!-- Affichage du bouton "Supprimer" -->
<form method="post">
    <input type="hidden" name="id" value="IDENTIFIANT_DU_POST_A_SUPPRIMER">
    <button type="submit">Supprimer</button>
</form>

  <?php if (!isset($_POST["register"]) && !isset($_POST["send_post"])) { ?>
    <!-- Formulaire d'inscription -->
    <h2>Inscription</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username"><br><br>
      <label for="email">Adresse e-mail :</label>
      <input type="email" id="email" name="email"><br><br>
      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password"><br><br>
      <input type="submit" name="register" value="S'inscrire">
    </form>

    <!-- Formulaire d'envoi de post -->