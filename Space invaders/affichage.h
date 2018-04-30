#ifndef __AFFICHAGE_YQ
#define __AFFICHAGE_YQ

#include "manip.h"

void affiche_tab(Space tab[LONGUEUR][LARGEUR], int post, int signaVaisseaux[max]);
int reconfigure_terminal (struct termios *prev);
int restaure_terminal (struct termios *prev);
void decompte();
void error_message(char* message);
void basic_message(char* message);
int choix_mod(char* mods[BUFFER]);
void boost_vit(Space tab[LONGUEUR][LARGEUR],int i, int j);
void boost_puis(Space tab[LONGUEUR][LARGEUR],int i, int j);
void boost_freq(Space tab[LONGUEUR][LARGEUR],int i, int j);
void boost_pv(Space tab[LONGUEUR][LARGEUR],int i, int j);
void boost_bombs(Space tab[LONGUEUR][LARGEUR],int i, int j);

#endif
