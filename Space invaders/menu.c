#include "menu.h"

//Créer un menu
menu_t* createMenu(const char* text){
  menu_t* menu = malloc(sizeof(menu_t));
  menu->pere = NULL;
  menu->k = 0;
  char* desc = malloc(strlen(text)+1);
  strcpy(desc,text);
  menu->description = desc;
  return menu;
}
//Ajoute une action à un menu
void addMenuAction(menu_t* m, const char* text, void(*f)()){
  char* desc = malloc(strlen(text)+1);
  strcpy(desc,text);
  m->tab[m->k].label = ACT;
  m->tab[m->k].act.description = desc;
  m->tab[m->k].act.fonc = f;
  m->k++;
}
//Ajoute un sous menu à un menu
void addSubMenu(menu_t* m, menu_t* sm){
  assert(sm->pere == NULL);
  sm->pere = m;
  m->tab[m->k].fiston = sm;
  m->k++;
}
//Ajoute un jeu à un menu
void addMenuJeu(menu_t* m, jeu_t jeux){
  m->tab[m->k].label = JEU;
  m->tab[m->k].jeu = jeux;
  m->k++;
}
//Vérifie que l'on ne dépasse pas les 9 éléments d'un menu
bool verif(long c, menu_t* m){
  if((c >= 1 && c <= ((m->k)+1)) || c == 10)  return true;
  else return false;
}
//Récupère une chaine de caractère et lui en enlève le '\n' et vide le buffer
int lire(char *chaine, int longueur){
    char *positionEntree = NULL;
    if (fgets(chaine, longueur, stdin) != NULL){
        positionEntree = strchr(chaine, '\n');
        if (positionEntree != NULL) *positionEntree = '\0';
        else viderBuffer(stdin);
        return 1;
   } else {
        viderBuffer(stdin);
        return 0;
    }
}
//Retourne soit p soit un chiffre entre 1 et 9
long entreeChar(const char* c){
  if(c[0] == 'p')return 10;
  else return strtol(c, NULL, 10);

}
//Fonction intermediaire pour lire et entreechar
long lireLong(){
    char nombreTexte[100] = {0};
    if (lire(nombreTexte, 100)) return entreeChar(nombreTexte);
    else return 0;
}
//Lance un menu
void launchMenu(menu_t* m){
  int i;
  long c;
  printf("%s :\n\n",m->description);
  for(i=0;i<(m->k);i++){
    switch(m->tab[i].label) {
      case ACT:
        printf("%d - %s\n\n",(i+1),m->tab[i].act.description);
        break;
      case SUBM:
        printf("%d - %s\n\n",(i+1),m->tab[i].fiston->description);
        break;
      case JEU:
        printf("%d - %s\n\n",(i+1),m->tab[i].jeu.titreJeu);
    }
  }
  c=lireLong();
  while(verif(c,m) == false) c = lireLong();
  system("clear");
  if(c != 10){
    switch(m->tab[c-1].label) {
      case ACT:
          m->tab[c-1].act.fonc();
          break;
      case SUBM:
          launchMenu(m->tab[c-1].fiston);
          break;
      case JEU:
          m->tab[c-1].jeu.fonc();
          break;
    }
  }else if(c == 10 && m->pere != NULL)launchMenu(m->pere);
 
}
//Affiche un menu
void afficheMenu(menu_t* m){
  printf("%s \n %d \n",m->description,m->k);
  int i;
  for(i=0;i<(m->k);i++){
    switch(m->tab[i].label) {
      case ACT:
         printf("%d - %s\n\n",i,m->tab[i].act.description);
         break;
      case SUBM:
         printf("%d - %s\n\n",i,m->tab[i].fiston->description);
         break;
      case JEU:
          printf("%d - %s\n\n",i,m->tab[i].jeu.titreJeu);
         break;
    }
  }
}
//Supprime un menu
void deleteMenu(menu_t* m){
  assert(m->pere == NULL);
  int i;
  for(i=0;i<9;i++){
    if(i >= 0 && i< m->k){
    switch(m->tab[i].label) {
      case ACT:
        free(m->tab[i].act.description);
        break;
      case SUBM:
        m->tab[i].fiston->pere = NULL;
        deleteMenu(m->tab[i].fiston);
        break;
      case JEU:
        free(m->tab[i].jeu.regles);
        free(m->tab[i].jeu.titreJeu);
        break;
    }
   }
  }
  free(m->description);
  free(m);
}