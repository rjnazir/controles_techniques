<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent ju ">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{jurl 'controles_techniques~default:index'}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{jurl 'controles_techniques~crbilanquotidien:index'}">Quotidien</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Visite technique
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~etat_vhladm_gn:index'}">Véhicules administratifs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~statbycentrebyusagebymonth2:index'}">Mensuelle par usage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~statbycentrebyusage:index'}">Trimestrielle par usage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~bilantrimestriel:index'}">Trimestrielle globale</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Réception technique
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~rt_stat_motif_centre_periode:index'}">Mensuelle par genre</a></li>
                        {* <li><hr class="dropdown-divider"></li> *}
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Constatation avant dédouanement
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{jurl 'controles_techniques~cad_stat_motif_centre_periode:index'}">Mensuelle par genre</a></li>
                        {* <li><hr class="dropdown-divider"></li> *}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>