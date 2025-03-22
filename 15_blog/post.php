<?php
  include_once("templates/header.php");

  if(isset($_GET['id'])) {

    $postId = $_GET['id'];
    $currentPost;

    foreach($posts as $post) {
      if($post['id'] == $postId) {
        $currentPost = $post;
      }
    }

  }

?>
  <main id="post-container">
    <div class="content-container">
      <h1 id="main-title"><?= $currentPost['title'] ?></h1>
      <p id="post-description"><?= $currentPost['description'] ?></p>
      <div class="img-container">
        <img src="<?= $BASE_URL ?>/img/<?= $currentPost['img'] ?>" alt="<?= $currentPost['title'] ?>">
      </div>
      <p class="post-content">Fala, pessoal! O submundo da cidade está fervendo com um rumor inesperado: a Gangue do Bozo, conhecida por sua excentricidade e estratégias imprevisíveis, estaria planejando trair a Maelstrom! Sim, parece que os palhaços querem virar o jogo contra seus próprios aliados.

Recentemente, os Bozos têm se aproximado da Maelstrom em um acordo que muitos acharam estranho desde o início. Afinal, combinar a criatividade caótica dos Bozos com o poder tecnológico da Maelstrom parecia uma receita para o caos total. Porém, fontes próximas sugerem que essa parceria pode ter sido só fachada. Os Bozos estariam usando o relacionamento para acessar os recursos tecnológicos da Maelstrom, enquanto tramam um golpe por baixo dos panos.

"É clássico deles, né? Eles fazem você achar que está no controle, mas no fundo já planejaram o próximo passo," comentou um informante local. Além disso, houve relatos de conversas secretas entre os Bozos e rivais da Maelstrom, além de um aumento suspeito no estoque de armas em territórios dominados pelos palhaços.

Se esses rumores forem verdadeiros, a traição pode transformar o submundo em um verdadeiro campo de batalha. A Maelstrom é conhecida por sua brutalidade e não deve deixar isso barato. "Se eles descobrirem, vai ter retaliação pesada. E, como sempre, quem vai sofrer são os civis presos no fogo cruzado," alertou um morador da zona de conflito.

Até agora, nem a Gangue do Bozo nem a Maelstrom fizeram declarações sobre o assunto. Mas o silêncio só deixa todo mundo mais desconfiado. Será que os Bozos vão conseguir enganar um dos grupos mais perigosos da cidade? Ou estão cavando sua própria cova com essa jogada arriscada? 🎭</p>
      
    </div>
    <aside id="nav-container">
      <h3 id="tags-title">Tags</h3>
      <ul id="tag-list">
        <?php foreach($currentPost['tags'] as $tag): ?>
          <li><a href="#"><?= $tag ?></a></li>
        <?php endforeach; ?>
      </ul>
      <h3 id="categories-title">Categorias</h3>
      <ul id="categories-list">
        <?php foreach($categories as $category): ?>
          <li><a href="#"><?= $category ?></a></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </main>
<?php
  include_once("templates/footer.php")
?>