#ifndef __SPACE_YQ
#define __SPACE_YQ

#include "manip.h"


void change_tableau(Space tab[LONGUEUR][LARGEUR], int timer, int fd, int tab_vaisseau[max]);
void init_tab_jeu(Space tab[LONGUEUR][LARGEUR]);
void copie_tab(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR], int k, int l, int i, int j, int tab_vaisseau[max]);
void destroy_obstacle(Space tab[LONGUEUR][LARGEUR], int i, int j);
void destroy_vaisseau(Space tab[LONGUEUR][LARGEUR], int signa, int tab_vaisseau[max]);
int tir_generation(Space tab[LONGUEUR][LARGEUR], int temps, int i, int j);
void swap_tab(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR]);
void dest_and_gen(Space tab[LONGUEUR][LARGEUR], int temps, int tab_vaisseau[max]);
void move_p_v(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR], char touche,int tab_vaisseau[max]);
void mouvement(Space tab[LONGUEUR][LARGEUR], char touche, int temps, int tab_vaisseau[max]);
int verif_col(Space tab[LONGUEUR][LARGEUR]);
void pop_obstacle(Space tab[LONGUEUR][LARGEUR]);
void error_message(char* message);
#endif