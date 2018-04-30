#include "space.h"
#include "affichage.h"

/*
Fonction : Modifie le tab du jeu pour creer les vaisseaux a l'instant(timing) et le met dans le tableau 
Entree : tableau du jeu, un temps en ms et un descripteur de fichier(niveaux)
Sortie : void
*/
void change_tableau(Space tab[LONGUEUR][LARGEUR], int timer, int fd, int tab_vaisseau[max]){
	chdir("../vaisseaux");
	char* buffer =malloc(BUFFER);
	char* bufferFgets = NULL;


	int curr_pos = lseek(fd,0,SEEK_CUR);
	int end_pos = lseek(fd, 0, SEEK_END);
	lseek(fd, curr_pos, SEEK_SET);

	timer = timer/1000;
	for(int debut = 0;lseek(fd,0,SEEK_CUR) != end_pos;debut++){
		curr_pos = lseek(fd,0,SEEK_CUR);

		myfgets(buffer,BUFFER,fd,bufferFgets);
		char* lettre = strpbrk(buffer," ");
		*lettre = '\0';
		char* vaisseau_a_creer = buffer;

		buffer = lettre + 1;
		lettre = strpbrk(buffer," ");
		*lettre = '\0';
		char* timing = buffer;

		if(atoi(timing) <= timer){
			buffer = lettre + 1;
			lettre = strpbrk(buffer," ");

			int a = atoi(buffer);
			buffer = lettre + 1;

			int b = atoi(buffer);
			Ship* v = create_ship(vaisseau_a_creer, tab_vaisseau);

			int fd1 = open(vaisseau_a_creer,O_RDONLY);
			if(fd1==-1)
				error_message("Caractéristiques du vaisseau non trouvées !");

			lseek(fd1,-(v->taille_longueur * v->taille_hauteur) - v->taille_hauteur + 1,SEEK_END);
			myfgets(buffer,BUFFER,fd1,bufferFgets);
			int compteur = 0;

			for(int i = 0;i<v->taille_hauteur;i++){
				for(int j = 0;j<v->taille_longueur;j++){
					tab[b+i][a+j].s_vaisseau = *(buffer+compteur);
					tab[b+i][a+j].vaisseau = v;
					compteur++;
				}
				myfgets(buffer,BUFFER,fd1,bufferFgets);
				compteur = 0;
			}
		}else{
			lseek(fd,curr_pos,SEEK_SET);
			break;
		}
	}
	chdir("../niveaux");
}

/*
Fonction : Initialise un tab du jeu (pour les caracteres le null est 'N')
Entree : tableau du jeu
Sortie : void
*/
void init_tab_jeu(Space tab[LONGUEUR][LARGEUR]){
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){
			tab[i][j].symbole = ' ';
			tab[i][j].vaisseau = NULL;
			tab[i][j].s_vaisseau = 'N';
			tab[i][j].projectile = NULL;
			tab[i][j].s_projectile = 'N';
		}
	}
}

/*
Fonction : Copie le tableau en deplaçant de k et de l (projectile et vaisseau)  
Entree : tableau du jeu, copie du tableau du jeu, k (depl vertical), l (depl horizontal), i(longeur),j (largeur) 
Sortie : void
*/
void copie_tab(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR], int k, int l, int i, int j, int tab_vaisseau[max]){
	if(tab[i][j].s_projectile != 'N'){
		newTab[i+k][j+l].projectile = tab[i][j].projectile;
		newTab[i+k][j+l].s_projectile = tab[i][j].s_projectile;
	}
	if(tab[i][j].s_vaisseau != 'N'){
		if(newTab[i+k][j+l].vaisseau != NULL && 
			tab[i][j].vaisseau != NULL){
			if(newTab[i+k][j+l].vaisseau->signature == -1){
				newTab[i+k][j+l].vaisseau = tab[i][j].vaisseau;
				newTab[i+k][j+l].vaisseau->point_vie--;
				newTab[i+k][j+l].s_vaisseau = tab[i][j].s_vaisseau;
			}else{
				destroy_vaisseau(tab, newTab[i+k][j+l].vaisseau->signature, tab_vaisseau);
				destroy_vaisseau(newTab, newTab[i+k][j+l].vaisseau->signature, tab_vaisseau);
				destroy_vaisseau(tab, tab[i][j].vaisseau->signature, tab_vaisseau);
				basic_message("Deux vaisseaux sont entrés en collision chef !\n");
			}
		}else{
			newTab[i+k][j+l].vaisseau = tab[i][j].vaisseau;
			newTab[i+k][j+l].s_vaisseau = tab[i][j].s_vaisseau;
		}
	}
}


/*Detruit l'osbtacle*/
void destroy_obstacle(Space tab[LONGUEUR][LARGEUR], int i, int j){
	tab[i][j].vaisseau = NULL;
	tab[i][j].s_vaisseau = ' ';
}

/*
Fonction : Detruit le vaisseau de signature signa  
Entree : tableau du jeu, la signature d'un vaisseau 
Sortie : void
*/
void destroy_vaisseau(Space tab[LONGUEUR][LARGEUR], int signa, int tab_vaisseau[max]){
	int trouve = 0;
	for(int i = 0;i<LONGUEUR;i++){
	    for(int j = 0;j<LARGEUR;j++){
	        if(tab[i][j].vaisseau != NULL){
	        	if(tab[i][j].vaisseau->signature == signa){
	            	tab[i][j].vaisseau = NULL;
	          		tab[i][j].s_vaisseau = 'N';
	          		trouve = 1;
	        	}
	        }
	    }
	}
	if(trouve){
		for(int i = 0; i < max ; i++){
			if(tab_vaisseau[i]==signa){
				int j;
				for(j = i+1; tab_vaisseau[j] != -1 && j < max; j++){
					tab_vaisseau[i++] = tab_vaisseau[j];
				}
				tab_vaisseau[j-1] = -1;
				break;
			}
		}
		(indice_vaisseau > 1)?indice_vaisseau--: indice_vaisseau;
	}
}



/*
Fonction : Tir le projectile   
Entree : tableau du jeu, temps, i(longeur),j (largeur) 
Sortie : etat 1 true 0 false
*/
int tir_generation(Space tab[LONGUEUR][LARGEUR], int temps, int i, int j){
	int calcul = temps - tab[i][j].vaisseau->dernier_tir;
	double cadence = calcul/1000;
	if(cadence >= tab[i][j].vaisseau->frequence_tir){
		tab[i][j].vaisseau->dernier_tir = temps;
		int h = 0;
		if(tab[i][j].vaisseau->signature == 0) h = - 1;
		else h = tab[i][j].vaisseau->taille_hauteur;
		int l = ((tab[i][j].vaisseau->taille_longueur+1) / 2 )-1;
		tab[i+h][j+l].s_projectile = tab[i][j].vaisseau->symbole_tir;
		tab[i+h][j+l].projectile = create_projectile(tab[i][j].vaisseau,-1);
		return 1;
	}
	return 0;
}


int power_generation(Space tab[LONGUEUR][LARGEUR], int a){
 	srand(time(NULL));
	int val = rand() % (FRQ_PUP);
	if(val == 10){
		int pos = rand() % (LARGEUR);
		tab[a][pos].s_projectile = '$';
		tab[a][pos].projectile = create_projectile(NULL,1);
	}
	return 0;
	
}
/*
Fonction : Echange les 2 tableaux   
Entree : tableau du jeu et le nouveau tableau du jeu
Sortie : void
*/
void swap_tab(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR]){
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){
			tab[i][j].vaisseau = newTab[i][j].vaisseau;
			tab[i][j].s_vaisseau = newTab[i][j].s_vaisseau;
			tab[i][j].projectile = newTab[i][j].projectile;
			tab[i][j].s_projectile = newTab[i][j].s_projectile;
		}
	}
}

void dest_and_gen(Space tab[LONGUEUR][LARGEUR], int temps, int tab_vaisseau[max]){
	int indice_vaisseau = taille_tab(tab_vaisseau);
	srand(time(NULL));
	int val = rand() % (indice_vaisseau);
	val = (val == 0)?1:val;
	int done = 9999;
	int choix_bonus = rand() % (4);
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){		
			if(tab[i][j].vaisseau != NULL){
				if(tab[i][j].projectile != NULL){
					if(tab[i][j].vaisseau->signature == 0 &&
						tab[i][j].projectile->power_up == 1){
							
							switch(choix_bonus){
								case 1:
								boost_vit(tab,i,j);
								break;
								case 2:
								boost_puis(tab,i,j);
								break;
								case 3:
								boost_freq(tab,i,j);
								break;
								case 4:
								boost_bombs(tab,i,j);
								break;
								default:
								boost_pv(tab,i,j);

							}
							
						}
					else tab[i][j].vaisseau->point_vie = tab[i][j].vaisseau->point_vie - tab[i][j].projectile->degats;
					tab[i][j].projectile = NULL;
					tab[i][j].s_projectile = 'N';
				}
				

				if(tab[i][j].vaisseau->signature != -1){
					if(tab[i][j].vaisseau->point_vie > 0 && tab[i][j].vaisseau->signature < done){  

						if(tab_vaisseau[val] == tab[i][j].vaisseau->signature){
							tir_generation(tab, temps, i, j);
							done = 1;
						}
					}
					if(tab[i][j].vaisseau->signature == 0)tir_generation(tab, temps, i, j);     

				}
				if(tab[i][j].vaisseau->point_vie <= 0){
					if(tab[i][j].vaisseau->signature == -1) destroy_obstacle(tab, i, j);
					else destroy_vaisseau(tab, tab[i][j].vaisseau->signature,tab_vaisseau); 


				}
			}
		}
	}
	
}

void destroy_all_vaisseau(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR], int tab_vaisseau[max]){
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){
			if(tab[i][j].vaisseau != NULL && tab[i][j].vaisseau->signature != 0){
				destroy_vaisseau(newTab, tab[i][j].vaisseau->signature, tab_vaisseau);
			}
			if(tab[i][j].projectile != NULL){
				tab[i][j].projectile = NULL;
				tab[i][j].s_projectile = 'N';
			}
		}
	}
}

/*
Fonction : Change la position des projectile et des vaisseaux dans le nouveau tableau 
Entree : tableau du jeu, le nouveau tableau du jeu et la touche
Sortie : void
*/
void move_p_v(Space tab[LONGUEUR][LARGEUR], Space newTab[LONGUEUR][LARGEUR], char touche,int tab_vaisseau[max]){
	char move;
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){
			if(tab[i][j].projectile != NULL){
				if(tab[i][j].projectile->signatureT == 0){
					if(i - tab[i][j].projectile->vitesse >= 0)
						copie_tab(tab,newTab,-1*(tab[i][j].projectile->vitesse),0,i,j,tab_vaisseau);
				}else
					if(i + tab[i][j].projectile->vitesse < LONGUEUR)
						copie_tab(tab,newTab,tab[i][j].projectile->vitesse,0,i,j,tab_vaisseau);
			}else if(tab[i][j].vaisseau != NULL){
				move = 0;

				if(tab[i][j].vaisseau->modele_vaisseau != 0){
					if(tab[i][j].vaisseau->modele_vaisseau == -1) move = 0;
					else move = check_liste(tab[i][j].vaisseau);
				}else if(touche != 0) move = touche;

				switch(move){
					case 'B':
					if(tab[i][j].vaisseau->nb_bombs > 0){
						destroy_all_vaisseau(tab, newTab, tab_vaisseau);
						tab[i][j].vaisseau->nb_bombs--;
					}
					move = 0;
					break;
					case 'Z':
					if(get_first(tab, tab[i][j].vaisseau->signature, 1)-5 <= -1) move = 0;
					break;
					case 'z':
					if(get_first(tab, tab[i][j].vaisseau->signature, 1)-1 == -1) move = 0;
					break;
					case 'S':
					if(get_last(tab, tab[i][j].vaisseau->signature, 1)+5 >= LONGUEUR) move = 0;
					break;
					case 's':
					if(get_last(tab, tab[i][j].vaisseau->signature, 1)+1 == LONGUEUR) move = 0;
					break;
					case 'D':
					if(get_last(tab, tab[i][j].vaisseau->signature, 2)+5 >= LARGEUR) move = 0;
					break;
					case 'd':
					if(get_last(tab, tab[i][j].vaisseau->signature, 2)+1 == LARGEUR) move = 0;
					break;
					case 'Q':
					if(get_first(tab, tab[i][j].vaisseau->signature, 2)-5 <= -1) move = 0;
					break;
					case 'q':
					if(get_first(tab, tab[i][j].vaisseau->signature, 2)-1 == -1) move = 0;
					break;
					default:
					move = move;
				}

				switch(move){
					case 'D':
					copie_tab(tab,newTab,0,5,i,j,tab_vaisseau);
					break;
					case 'd':
					copie_tab(tab,newTab,0,1,i,j,tab_vaisseau);
					break;
					case 'Q':
					copie_tab(tab,newTab,0,-5,i,j,tab_vaisseau);
					break;
					case 'q' :
					copie_tab(tab,newTab,0,-1,i,j,tab_vaisseau);
					break;
					case 'Z':
					copie_tab(tab,newTab,-5,0,i,j,tab_vaisseau);
					break;
					case 'z':
					copie_tab(tab,newTab,-1,0,i,j,tab_vaisseau);
					break;
					case 'S':
					copie_tab(tab,newTab,5,0,i,j,tab_vaisseau);
					break;
					case 's':
					copie_tab(tab,newTab,1,0,i,j,tab_vaisseau);
					break;
					default:
					copie_tab(tab,newTab,0,0,i,j,tab_vaisseau);
				}
			}
		}
	}
}

/*
Fonction : Effectue le mouvement des projectiles, des vaisseaux et detruit vaisseau   
Entree : tableau du jeu, touche du joueur, temps du tir
Sortie : void
*/
void mouvement(Space tab[LONGUEUR][LARGEUR], char touche, int temps, int tab_vaisseau[max]){

	Space newTab[LONGUEUR][LARGEUR];
	init_tab_jeu(newTab);

	
	
	
	move_p_v(tab, newTab, touche,tab_vaisseau);
	power_generation(newTab,0);

	swap_tab(tab, newTab);
	dest_and_gen(tab, temps, tab_vaisseau);

}





/*
Fonction : Verification de si on touche un vaisseau ennemi   
Entree : tableau du jeu
Sortie : si int 2 perdu sinon 0
*/
int verif_col(Space tab[LONGUEUR][LARGEUR]){
	int ligne = get_first(tab, 0, 1);
	for(int i = LONGUEUR - 1;i>=ligne;i--){
		for(int j = LARGEUR - 1;j>=0;j--){
			if(tab[i][j].vaisseau!= NULL && tab[i][j].vaisseau->modele_vaisseau != 0 && tab[i][j].vaisseau->signature != -1){
				return 2;
			}
		}
	}
	return 0;
}





/*
Fonction : Ouvre le fichier obstacle et creer les obstacles dans le tableau de jeu
Entree : tableau du jeu
Sortie : void
*/
void pop_obstacle(Space tab[LONGUEUR][LARGEUR]){
	chdir("..");
	int fd = open("obstacle",O_RDONLY);
	if(fd == -1) return;
	char* buffer = malloc(BUFFER);
	char* bufferFgets = NULL;
	char* lettre;
	int pos = lseek(fd, 0, SEEK_END);
	lseek(fd, 0, SEEK_SET);
	while(lseek(fd,0,SEEK_CUR) != pos){
		myfgets(buffer,BUFFER,fd,bufferFgets);
		lettre = strpbrk(buffer," ");
		*lettre = '\0';
		int a = atoi(buffer);
		buffer = lettre + 1;
		int b = atoi(buffer);
		Ship* obstacle = (Ship*) malloc(sizeof(Ship));
		obstacle->taille_hauteur = 1;
		obstacle->pattern_cycle = 0;
		obstacle->deplacement = NULL;
		obstacle->point_vie = 1;
		obstacle->frequence_tir = 0.;
		obstacle->vitesse_tir = 0;
		obstacle->puissance_tir = 0;
		obstacle->symbole_tir = 0;
		obstacle->modele_vaisseau = -1;
		obstacle->signature = -1;
		obstacle->dernier_tir = -1;
		obstacle->nb_bombs = 0;
		tab[b][a].vaisseau = obstacle;
		tab[b][a].s_vaisseau = '#';
	}
	chdir("niveaux");
}
