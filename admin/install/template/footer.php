        </div><!--/span-->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
          <div class="list-group">
              <?php 
              if($_SERVER['argv'][0] == false) {
                  echo '<a href="#" class="list-group-item active">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item disabled">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item disabled">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item disabled">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item disabled">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item disabled">Успешная установка</a>';                  
              }elseif($_SERVER['argv'][0] == 'step=welcome') {
                  echo '<a href="#" class="list-group-item active disabled">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item active">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item disabled">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item disabled">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item disabled">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item disabled">Успешная установка</a>';   
              }elseif($_SERVER['argv'][0] == 'step=tables') {
                  echo '<a href="#" class="list-group-item active disabled">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item active disabled">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item active">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item disabled">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item disabled">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item disabled">Успешная установка</a>';   
              }elseif($_SERVER['argv'][0] == 'step=mysqli') {
                  echo '<a href="#" class="list-group-item active disabled">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item active disabled">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item active disabled">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item active">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item disabled">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item disabled">Успешная установка</a>';   
              }elseif($_SERVER['argv'][0] == 'step=profiles') {
                  echo '<a href="#" class="list-group-item active disabled">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item active disabled">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item active disabled">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item active disabled">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item active">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item disabled">Успешная установка</a>';   
              }elseif($_SERVER['argv'][0] == 'step=finish') {
                  echo '<a href="#" class="list-group-item active disabled">Выбор языка</a>';
                  echo '<a href="#" class="list-group-item active disabled">Начало Установки</a>';
                  echo '<a href="#" class="list-group-item active disabled">Подключение к базе данных</a>';
                  echo '<a href="#" class="list-group-item active disabled">Установка Таблиц</a>';
                  echo '<a href="#" class="list-group-item active disabled">Регистрация администратора</a>';
                  echo '<a href="#" class="list-group-item active">Успешная установка</a>';   
              }
              
              ?>
          </div>
        </div><!--/span-->
      </div><!--/row-->

  <!--  <div class="navbar navbar-fixed-bottom navbar-default" role="navigation">
      <div class="container">
        <div class="collapse navbar-collapse pull-right">
          <ul class="nav navbar-nav">
           <li><a href="http://shcms.ru">Powered by SHCMS Engine © 2015</a></li>
          </ul>
        </div>
      </div>
    </div>
    -->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="offcanvas.js"></script>
  </body>
</html>