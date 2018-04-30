#ifndef __MANIP_YQ
#define __MANIP_YQ

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/stat.h>
#include <math.h>
#include <pthread.h>
#include <termios.h>
#include <unistd.h>
#include <sys/types.h>
#include <fcntl.h>
#include <poll.h>
#include <time.h>
#include <dirent.h>

#define BUFFER 2048
#define LONGUEUR 50
#define LARGEUR 100
#define TIME_RFRCH 1000
#define FRQ_PUP 30

typedef struct Tir_s{
    int signatureT;
    int degats;
    int vitesse;
    char symboleT;
    int power_up;

} Tir;

typedef struct ListeChainee_s{
    int deplacement_horizontal;
    int deplacement_vertical;
    struct ListeChainee_s* next;
} ListeChainee;

typedef struct Ship_s{
    int taille_longueur;
    int taille_hauteur;
    int pattern_cycle;
    ListeChainee* deplacement;
    int point_vie;
    double frequence_tir;
    int vitesse_tir;
    int puissance_tir;
    char symbole_tir;
    int modele_vaisseau;
    int signature;
    int dernier_tir;
    int nb_bombs;
} Ship;

typedef struct Space_s{
    char symbole;
    Ship* vaisseau;
    char s_vaisseau;
    Tir* projectile;
    char s_projectile;
} Space;



struct termios prev;
int old_taille_lignes;
int old_taille_colonnes;
int curr_vaisseau;
int max;
int indice_vaisseau;


void myfgets(char* buffer, int length, int fd, char* bufferFgets);
ListeChainee* InitListe(int taille);
void create_deplacementHorizontal(ListeChainee* liste, char* s,int taille);
void create_deplacementVertical(ListeChainee* liste, char* s, int taille);
void init_tab(int tab[max]);
Ship* create_ship(char* name, int tabVaisseau[max]);
char check_liste(Ship* v);
char convert_arrow();
Tir* create_projectile(Ship* v, int power);
int taille_tab(int *tab);
int contains(int signaVaisseaux[max], int val);
void sleep_ms(int milliseconds);
void get_max(int fd);
void init_const();
int is_dir(struct dirent* dir);
int end(Space tab[LONGUEUR][LARGEUR]);
void get_games(char* buffer);
int get_first(Space tab[LONGUEUR][LARGEUR], int signa, int x_y);
int get_last(Space tab[LONGUEUR][LARGEUR], int signa, int x_y);

int main_game(char* file);

#endif