#include "affichage.h"
#include "manip.h"
#include "space.h"



/*
Fonction : Lance le jeu et verifie la saisie 
Entree : nom du fichier du niveau
Sortie : int
*/
int main_game(char* file){

  if(reconfigure_terminal(&prev)==-1)
    error_message("Problème sur l'écran de contrôle !\n");


  int fd = open(file,O_RDONLY);
  if(fd == -1)
    error_message("Les coordonnées du champ de bataille n'ont pas été trouvées !\n");

  init_const();
  get_max(fd);

  Space tab[LONGUEUR][LARGEUR];
  init_tab_jeu(tab);

  int tabVaisseau[max];
  init_tab(tabVaisseau);

  int signaVaisseaux[max];
  init_tab(signaVaisseaux);

  change_tableau(tab, 0, fd,tabVaisseau);
  pop_obstacle(tab);
  
  affiche_tab(tab, 0, signaVaisseaux);
  decompte();

  struct pollfd pfd[1];
  pfd[0].fd = STDIN_FILENO;
  pfd[0].events = POLLIN | POLLHUP;

  int partie_finie, temps_poll = TIME_RFRCH, temps_actuel, retour_poll, temps_total = 0;

  while((partie_finie = end(tab)) == 0){
    temps_total += temps_poll;
    temps_actuel = (int)time(NULL);
    retour_poll = poll(pfd,1,temps_poll);

    if(retour_poll > 0){

      char direction = convert_arrow();
      if(direction == 0)
        error_message("Vous avez choisi de partir de la bataille...\n");

      sleep_ms(temps_poll - ((int)time(NULL) - temps_actuel));
      mouvement(tab, direction, temps_total, tabVaisseau);

    }else mouvement(tab, 0, temps_total,tabVaisseau);

    affiche_tab(tab, 1, signaVaisseaux);
    
    if((partie_finie = verif_col(tab)) == 2) goto fin; 

    init_tab(signaVaisseaux);

    if(curr_vaisseau != max)
      change_tableau(tab, temps_total, fd,tabVaisseau);

  }

  fin:
  if(partie_finie == 2)
    return -1;

  basic_message("Gagne ! Il ne reste plus une miette de ces adversaires.\n");
  sleep(5);
  return 0;
}

int main(){
    char* mods[BUFFER];
    int choix = choix_mod(mods);

    get_games(mods[choix]);
    chdir("..");

  return 0;
}