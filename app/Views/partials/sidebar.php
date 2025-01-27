<div class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="info">
      <p>
      <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR', 'ETUDIANT', 'INVITE'])): ?>
    <h6 style="font-size: medium;"> PRIVILEGE : </h6>
    <span class="btn btn-rounded btn-warning btn-sm">
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
        } elseif ($role === 'INVITE') {
          if ($civilite === 'Mme') {
              echo 'INVITEE';
          } else {
              echo 'INVITE';
          }
      }
        ?>
    </span>
<?php endif; ?>

      </p>
      <a href="<?= base_url('user/update') ?>"><i class="fa fa-cog"></i></a><a href="<?= base_url('auth/logout') ?>"><i
          class="fa fa-power-off"></i></a>
    </div>
  </div>

  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">NAVIGATION</li>
    <li> <a href="<?= base_url('dashboard') ?>"> <i class="fa fa-dashboard"></i> <span>Tableau de bord</span> <span
          class="pull-right-container"> </span> </a>
    </li>
    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
      <li> <a href="<?= base_url('auteurs') ?>"> <i class="fa fa-user"></i> <span>Auteurs</span> <span
            class="pull-right-container"> </span> </a>
      </li>
    <?php endif; ?>
    <li> <a href="<?= base_url('memoires') ?>"> <i class="fa fa-book"></i> <span>Mémoires</span> <span
          class="pull-right-container"> </span> </a>
    </li>
    <li class="treeview"> <a href="#"> <i class="fa fa-book"></i> <span>Livres</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
        <li><a href="<?= base_url('livres') ?>">Livres disponibles</a></li>
        <li><a href="<?= base_url('recommandations') ?>">Recommandations</a></li>
      </ul>
    </li>
    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'ETUDIANT', 'PROFESSEUR'])): ?>
    <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Gestion des comptes</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
      <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <li><a href="<?= base_url('user/administrateur') ?>">Administrateurs</a></li>
        <?php endif; ?>
        <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR', 'INVITE'])): ?>
          <li><a href="<?= base_url('user/professeur') ?>">Professeurs</a></li>
        <?php endif; ?>
        <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'ETUDIANT', 'INVITE'])): ?>
          <li><a href="<?= base_url('user/index') ?>">Étudiants</a></li>
        <?php endif; ?>
      </ul>
    </li>
    <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Gestion des emprunts</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <li><a href="<?= base_url('emprunts') ?>" class="active">Affecter un emprunt</a></li>
        <?php else: ?>
          <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <li><a href="<?= base_url('emprunts') ?>" class="active">Voir mes emprunts</a></li>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <li><a href="<?= base_url('emprunts/retournes') ?>">Emprunts retournés</a></li>
          <li><a href="<?= base_url('emprunts/encours') ?>">Emprunts en cours</a></li>
        <?php endif; ?>
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <li><a href="<?= base_url('emprunts/historiques') ?>">Historique des emprunts</a></li>
        <?php else: ?>
          <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <li><a href="<?= base_url('emprunts/historiques') ?>">Historique de mes emprunts</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>
      <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'ETUDIANT', 'PROFESSEUR'])): ?>
    <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Gest. des réservations</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
      <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR','ETUDIANT', 'PROFESSEUR'])): ?>
        <li><a href="<?= base_url('reservations') ?>">Réservation des livres</a></li>
        <?php endif; ?>
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <li><a href="<?= base_url('reservations/historiques') ?>">Historique des réservations</a></li>
        <?php else: ?>
          <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <li><a href="<?= base_url('reservations/historiques') ?>">Historique de mes réservations</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </li>
    
    <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Gestion des visites</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
      <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'INVITE'])): ?>
          <li><a href="<?= base_url('visites') ?>">Affecter une visite</a></li>
        <?php else: ?>
          <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <li><a href="<?= base_url('visites') ?>">Voir mes visites</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>
    <li class="treeview"> <a href="#"> <i class="fa fa-envelope"></i> <span>Gestion de mailbox</span> <span
          class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
        <li><a href="<?= base_url('mailbox') ?>">Envoie des mails</a></li>
      </ul>
    </li>
    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-edit"></i> <span>Configurations</span> <span
            class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li><a href="<?= base_url('categories') ?>">Catégorie</a></li>
          <li><a href="<?= base_url('cycles') ?>">Cycle</a></li>
          <li><a href="<?= base_url('rangers') ?>">Rangée</a></li>
          <li><a href="<?= base_url('casiers') ?>">Casier</a></li>
          <li><a href="<?= base_url('filieres') ?>">Filière</a></li>
          <li><a href="<?= base_url('motif-visites') ?>">Motif de la visite</a></li>
        </ul>
      </li>
    <?php endif; ?>
  </ul>
</div>