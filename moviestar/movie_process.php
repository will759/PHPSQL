<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Resgata dados do usuário
$userData = $userDao->verifyToken();

if ($type === "create") {

    // Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    // Validação mínima de dados
    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;

        // Upload de imagem do filme
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            // Checando tipo da imagem
            if (in_array($image["type"], $imageTypes)) {

                // Checa se imagem é jpg
                if (in_array($image["type"], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }

                // Gerando o nome da imagem
                $imageName = $movie->imageGenerateName();

                // Verificar se o diretório existe
                $uploadDir = "./img/movies/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                imagejpeg($imageFile, $uploadDir . $imageName, 100);
                $movie->image = $imageName;

            } else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                exit;
            }
        }

        // Log para monitorar os dados que estão sendo enviados
        error_log("Tentando salvar filme: " . print_r($movie, true));

        // Tenta salvar o filme no banco
        try {
            $movieDao->create($movie);
            error_log("Filme salvo com sucesso!");
        } catch (Exception $e) {
            error_log("Erro ao salvar filme: " . $e->getMessage());
            $message->setMessage("Erro ao salvar o filme, tente novamente.", "error", "back");
        }

    } else {
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
    }

} else if ($type === "delete") {

    // Recebe os dados do form
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if ($movie) {

        // Verificar se o filme é do usuário
        if ($movie->users_id === $userData->id) {

            $movieDao->destroy($movie->id);

        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }

    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }

} else if ($type === "update") {

    // Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    if ($movieData) {

        // Verificar se o filme é do usuário
        if ($movieData->users_id === $userData->id) {

            if (!empty($title) && !empty($description) && !empty($category)) {

                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;

                if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];

                    if (in_array($image["type"], $imageTypes)) {

                        if (in_array($image["type"], $jpgArray)) {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }

                        $imageName = $movie->imageGenerateName();

                        $uploadDir = "./img/movies/";
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        imagejpeg($imageFile, $uploadDir . $imageName, 100);

                        $movieData->image = $imageName;

                    } else {
                        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                        exit;
                    }
                }

                $movieDao->update($movieData);

            } else {
                $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
            }

        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }

    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }

} else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
}
