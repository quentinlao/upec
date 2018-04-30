#ifndef __MENU_H_YQ_
#define __MENU_H_YQ_

typedef struct{
  char* titreJeu;
  void (*fonc)();
} jeu_t;

typedef struct{
  char* description;
  void (*fonc)();
} action_t;

typedef struct item_u{
  union{
    struct menu_s* fiston;
    action_t act;
    jeu_t jeu;
  };
  enum { SUBM, ACT, JEU } label;
} item_t;

typedef struct menu_s {
  struct menu_s* pere;
  char* description;
  item_t tab[9];
  int k;
} menu_t;

menu_t* createMenu(const char* text);
void addMenuAction(menu_t* m, const char* text, void(*f)());
void addSubMenu(menu_t* m, menu_t* sm);
void addMenuJeu(menu_t* menu, jeu_t jeu);
bool verif(long c, menu_t* m);
int lire(char *chaine, int longueur);
long entreeChar(const char* c);
long lireLong();
void launchMenu(menu_t* m);
void afficheMenu(menu_t* m);
void deleteMenu(menu_t* m);

#endif