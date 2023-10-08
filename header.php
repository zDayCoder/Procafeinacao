<style>
body {margin:0;}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
  position: relative;
  top: 0;
  width: 100%;
}

li {
  float: left;
}

li a {
  font-family: Verdana;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #04AA6D;
}
.active:hover{
  background-color: #000;
}

li a:hover:not(.active),.active,li a{
  transition: 0.2s;
  text-decoration: none !important;
  color: white !important;
}

</style>

<ul>
<li><a href="javascript:window.location.reload();">Início</a></li>
  <li><a href="/TCC/Procafeinacao/acesso/register">Cadastre-se</a></li>
  <li><a href="/TCC/Procafeinacao/acesso/login">Entrar</a></li>
  <li style="float:right"><a class="active" href="javascript:alert('Ainda nao temos nada a exibir');">Sobre</a></li>
</ul>
<br><br>
