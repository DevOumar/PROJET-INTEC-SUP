<nav class="navbar blue-bg navbar-static-top">
  <!-- Sidebar toggle button-->
  <ul class="nav navbar-nav pull-left">
    <li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
  </ul>
  <div class="pull-left search-box">
    <form action="<?= base_url('livres/search')?>" method="post" class="search-form">
    <?= csrf_field() ?>
      <div class="input-group">
        <input name="query" class="form-control" placeholder="Rechercher un livre..." type="text">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>
    <!-- search form -->
  </div>
  
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- Messages: style can be found in dropdown.less-->
      <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i
            class="fa fa-envelope-o"></i>
          <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
        </a>
        
        <ul class="dropdown-menu">
          <li class="header">Vous avez 1 message</li>
          <li>
            <ul class="menu">
              <li><a href="#">
                  <div class="pull-left"><img src="public/dist/img/avatar1.png" class="img-circle" alt="User Image"> <span
                      class="profile-status online pull-right"></span></div>
                  <h4>Oumar CISSE</h4>
                  <p>J'ai terminé! A bientôt...</p>
                  <p><span class="time">9:30 AM</span></p>
                </a></li>
              
            </ul>
          </li>
          <li class="footer"><a href="#">Voir tous les messages</a></li>
        </ul>
      </li>
      <!-- Notifications: style can be found in dropdown.less -->
      <li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <div class="notify"><span class="badges"><?= $countUnreadNotifications ?></span></div>
    </a>
    <ul class="dropdown-menu">
        <li class="header">Notifications</li>
        <li>
            <ul class="menu">
                <?php foreach ($notifications as $notification): ?>
                    <li>
                    <a href="<?= base_url('notifications/markAsRead/' . $notification->id) ?>">
                        <div class="notification-circle <?= ($notification->status === 'unread') ? 'unread' : 'read' ?>"></div>
                            <p><?= $notification->message ?></p>
                            <p><span class="time"><?= date('d/m/Y \à H:i', strtotime($notification->created_at)) ?></span></p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="footer"><a href="<?= base_url('notifications') ?>">Voir toutes les notifications</a></li>
    </ul>
</li>

      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu p-ph-res">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR', 'ETUDIANT', 'INVITE'])): ?>
            <?php if (session()->has('photo') && !empty(session()->get('photo'))): ?>
        <img src="<?= base_url('public/files/users_upload/' . session()->get('photo')) ?>" class="user-image" alt="Photo de profil">
    <?php else: ?>
        <img src="<?= base_url('public/dist/img/avatar1.png') ?>" class="user-image" alt="User Image">
    <?php endif; ?>
<?php endif; ?>
          <span class="d-none d-lg-inline" id="logged-user" data-name="<?= esc(mb_ucwords(session()->get('prenom'))) ?>
            <?= esc(mb_strtoupper(session()->get('nom'), 'UTF-8')) ?>">
          <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR', 'ETUDIANT', 'INVITE'])): ?>

            <?= esc(mb_ucwords(session()->get('prenom'))) ?>
            <?= esc(mb_strtoupper(session()->get('nom'), 'UTF-8')) ?> |
            
      <?php
        $role = strtoupper(session()->get('role'));
        $civilite = session()->get('civilite');

        if ($role === 'ADMINISTRATEUR') {
            if ($civilite === 'Mme') {
                echo 'ADMINISTRATRICE';
            } else {
                echo 'ADMINISTRATEUR';
            }
        } elseif ($role === 'ETUDIANT') {
            if ($civilite === 'Mme') {
                echo 'ETUDIANTE';
            } else {
                echo 'ETUDIANT';
            }
        } elseif ($role === 'PROFESSEUR') {
            if ($civilite === 'Mme') {
                echo 'PROFESSEURE';
            } else {
                echo 'PROFESSEUR';
            }
        }elseif ($role === 'INVITE') {
          if ($civilite === 'Mme') {
              echo 'INVITEE';
          } else {
              echo 'INVITE';
          }
      }
        ?>
        <?php endif; ?>
          </span>

        </a>
        <ul class="dropdown-menu">
          <li class="user-header">
            <div class="pull-left user-img"></div>
            <p class="text-left">
              <?= esc(mb_strtoupper(session()->get('pseudo'), 'UTF-8')) ?> <a><i class="fa fa-circle text-success"></i> en ligne</a><small>
                <?= session()->get('email') ?>
              </small>
            </p>
            <div class="view-link text-left"><a href="<?= base_url('user/update') ?>" class="mr-10 btn btn-xs btn-secondary show-details"
                data-toggle="tooltip" class="mr-10 btn btn-xs btn-secondary" data-toggle="modal-show">Voir le profil</a>
            </div>
          </li>
          <li role="separator" class="divider"></li>
          <li><a href="<?= base_url('auth/resetpasswordUser') ?>"><i class="icon-gears"></i> Changer mot de passe</a>
          </li>
          <li role="separator" class="divider"></li>
          <li><a href="<?= base_url('auth/logout') ?>"><i class="fa fa-power-off"></i>Se déconnecter</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>