#include "affichage.h"

/*
Fonction : Affiche le tableau du jeu et pour l'init (post) on cree le tableau de signature
Entree : tableau du jeu, entier post pour savoir l'etat, entier max et un tableau de signature
Sortie : void
*/
void affiche_tab(Space tab[LONGUEUR][LARGEUR], int post, int signaVaisseaux[max]){
    static char buff_affic[(LARGEUR+2)*(LONGUEUR+2) + 1];
    buff_affic[0]= '\0';

    int int_buff = 0;
    int compteur = 0;

    for(int i = 0;i<LARGEUR+1;i++) sprintf(buff_affic+(int_buff++),"-");
    sprintf(buff_affic+(int_buff++),"\n");

    for(int i = 0;i<LONGUEUR;i++){
        for(int j = 0;j<LARGEUR;j++){
            if(tab[i][j].s_vaisseau != 'N') tab[i][j].symbole = tab[i][j].s_vaisseau;
            else if(tab[i][j].s_projectile != 'N') tab[i][j].symbole = tab[i][j].s_projectile;
            else tab[i][j].symbole = ' ';

            sprintf(buff_affic+(int_buff++),"%c", tab[i][j].symbole);

            if(post){
                if(tab[i][j].vaisseau != NULL){
                    if(tab[i][j].vaisseau->signature != -1 && contains(signaVaisseaux, tab[i][j].vaisseau->signature) == 0){
                        signaVaisseaux[compteur++] = tab[i][j].vaisseau->signature;
                        tab[i][j].vaisseau->deplacement = tab[i][j].vaisseau->deplacement->next;
                    }
                }
            }
        }

        sprintf(buff_affic+(int_buff),"|\n");
        int_buff += 2;
    }

    for(int i = 0;i<LARGEUR+1;i++) sprintf(buff_affic+(int_buff++),"-");
    sprintf(buff_affic+(int_buff++),"\n");

    write(STDOUT_FILENO,buff_affic,int_buff); 
}

/* cette fonction reconfigure le terminal, et stocke la configuration initiale a l'adresse prev */
int reconfigure_terminal (struct termios *prev){
    struct termios new;
    if(tcgetattr(STDIN_FILENO,prev)==-1)
        return -1;

    new.c_iflag=prev->c_iflag;//entree
    new.c_oflag=prev->c_oflag;//sortie
    new.c_cflag=prev->c_cflag;//controle
    new.c_lflag=0;//mode locaux
    new.c_cc[VMIN]=0;
    new.c_cc[VTIME]=0;
    new.c_cc[VINTR]=0;

    if(tcsetattr(STDIN_FILENO,TCSANOW,&new)==-1)
        return -1;

    char* buffer = malloc(9);
    sprintf(buffer, "\x1b[?25l");
    write(STDOUT_FILENO, buffer, 9);
    free(buffer);

    return 0;
}

/* cette fonction restaure le terminal avec la configuration stockee a l'adresse prev */
int restaure_terminal (struct termios *prev){
    char* buffer = malloc(9);
    sprintf(buffer, "\x1b[?25h");
    write(STDOUT_FILENO, buffer, 9);
    free(buffer);

    return tcsetattr(STDIN_FILENO,TCSANOW,prev);
}

/* Effectue un decompte de 3 */
void decompte(){
    int compteur = 0;
    char* buff_affic = malloc(5);
    basic_message("Préparez-vous, le jeu va commencer dans ");
    while(compteur != 3){
        sprintf(buff_affic,"%d... ", (3-(compteur++)));
        write(STDOUT_FILENO, buff_affic, 5);
        fflush(stdout);
        sleep(1);
    }
    basic_message("\n");
}
/* Ecrit sur la sortie et remet l'ancien terminal */
void error_message(char* message){
    write(STDOUT_FILENO, message, strlen(message));
    restaure_terminal(&prev);
    exit(0);
}

/* Ecrit sur la sortie le message */
void basic_message(char* message){
    write(STDOUT_FILENO, message, strlen(message));
}
/* Choix du mode */
int choix_mod(char* mods[BUFFER]){
    char buf[BUFFER];
    getcwd(buf,BUFFER);

    DIR *drip = opendir(buf);
    struct dirent *dir;
    basic_message("Voici la liste des galaxie disponibles (mod) : \n\n");

    char buff_affic[BUFFER];
    int compteur = 0, total_lettres = 0;

    while((dir=readdir(drip))!=NULL){
        if(is_dir(dir)){
            if(strcmp(".",dir->d_name)!=0 && strcmp("..",dir->d_name)!=0 && strcmp(".git",dir->d_name)!=0){

                if(compteur < 10){
                    sprintf(buff_affic+total_lettres, "0%d : %s\n", (compteur+1) ,dir->d_name);
                    total_lettres += 6 + strlen(dir->d_name);
                }else{
                    sprintf(buff_affic+total_lettres, "%d : %s\n", (compteur+1) ,dir->d_name);
                    total_lettres += 5 + strlen(dir->d_name);
                }

                mods[compteur] = malloc(64);
                strcpy(mods[compteur++], dir->d_name);
            }
        }
    }

    write(STDOUT_FILENO,buff_affic,total_lettres); 
    basic_message("Entrez le numéro du mod que vous voulez lancer :\n");

    char* buffer =malloc(3);
    char* bufferFgets = NULL;
    myfgets(buffer, 3, STDIN_FILENO, bufferFgets);

    if(buffer[0] == 'p' || buffer[1] == 'p') exit(0);
    int choix = atoi(buffer) - 1;

    while(choix < 0 || choix > compteur){
        basic_message("Sélectionnez des coordonnées correctes !\n");

        myfgets(buffer, 3, STDIN_FILENO, bufferFgets);

        if(buffer[0] == 'p' || buffer[1] == 'p') exit(0);
        choix = atoi(buffer) - 1;
    }

    return choix;
}

void boost_vit(Space tab[LONGUEUR][LARGEUR],int i, int j){
    basic_message("La vitesse de tir a été augmenté !\n");
    tab[i][j].vaisseau->vitesse_tir += 1;
}
void boost_puis(Space tab[LONGUEUR][LARGEUR],int i, int j){
    basic_message("La puissance a été augmenté !\n");
    tab[i][j].vaisseau->puissance_tir += 25;
}
void boost_freq(Space tab[LONGUEUR][LARGEUR],int i, int j){
    basic_message("La fréquence de tir a été augmenté !\n");
    tab[i][j].vaisseau->frequence_tir += 1.5;
}
void boost_pv(Space tab[LONGUEUR][LARGEUR],int i, int j){
    basic_message("La vie a été augmenté !\n");
    tab[i][j].vaisseau->point_vie += 10;
}
void boost_bombs(Space tab[LONGUEUR][LARGEUR],int i, int j){
    basic_message("Le nombre de bombe a été augmenté !\n");
    tab[i][j].vaisseau->nb_bombs += 1;
}