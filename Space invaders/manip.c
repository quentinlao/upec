#include "manip.h"

#include "affichage.h"

/*
Fonction : Recupere une ligne jusqu'au \n dans le buffer du descripteur fd
Entree : buffer, taille du buffer, descripteur de fichier, bufferpoubelle à null
Sortie : void
*/
void myfgets(char* buffer, int length, int fd, char* bufferFgets){
	if(length > 0 || strlen(buffer) > 0){
		if(bufferFgets == NULL){
			bufferFgets = malloc(length);
			read(fd,bufferFgets,length);
		}
		char* lettre = strpbrk(bufferFgets,"\n");
		if(lettre != NULL){
			int taille = strlen(lettre);
			*lettre = '\0';
			strcpy(buffer, bufferFgets);
			bufferFgets = lettre + 1;
			lseek(fd, -1*taille + 1, SEEK_CUR);
		}
		else if(lettre == NULL){
			int pos = lseek(fd, 0, SEEK_CUR);
			int end = lseek(fd, 0, SEEK_END);
			if(pos == end){
				strcpy(buffer,bufferFgets);
				return;
			} else lseek(fd, pos, SEEK_SET);
			int taille = strlen(bufferFgets);
			strcpy(buffer,bufferFgets);
			char* newP = buffer + taille;
			if(taille != length) lseek(fd, taille, SEEK_CUR);
			bufferFgets = NULL;
			myfgets(newP,length - taille,fd, bufferFgets);
		}
	}
}

/*
Fonction : Initialisation de la listeChainee (liste circulaire)
Entree : taille de la liste
Sortie : ListeChainee
*/
ListeChainee* InitListe(int taille){
	ListeChainee* liste = (ListeChainee*) malloc(sizeof(ListeChainee));
	ListeChainee* newL = liste;
	for(int i =0;i<taille-1; i++){
		liste->deplacement_horizontal = 0;
		liste->deplacement_vertical = 0;
		ListeChainee* new = (ListeChainee*) malloc(sizeof(ListeChainee));
		liste->next = new;
		liste = liste->next;
	}
	liste->next = newL;
	return newL;
}

/*
Fonction : Affecte le pattern du deplacement horizontal à la liste 
Entree : la liste chainee, la chaine du pattern et la taille
Sortie : void
*/
void create_deplacementHorizontal(ListeChainee* liste, char* s,int taille){
	char* lettre = NULL;
	for(int i =0;i<taille; i++){
		liste->deplacement_horizontal = atoi(s);
		lettre = strpbrk(s," ");
		if(lettre != NULL) s = lettre + 1;
		liste = liste->next;
	}
	liste = liste->next;
}

/*
Fonction : Affecte le pattern du deplacement vertical à la liste 
Entree : la liste chainee, la chaine du pattern et la taille
Sortie : void
*/
void create_deplacementVertical(ListeChainee* liste, char* s, int taille){
	char* lettre = NULL;
	for(int i =0;i<taille; i++){
		liste->deplacement_vertical = atoi(s);
		lettre = strpbrk(s," ");
		if(lettre != NULL) s = lettre + 1;
		liste = liste->next;
	}
	liste = liste->next;
}

/*
Fonction : Initialise un tableau à -1  
Entree : tableau de taille max
Sortie : void
*/
void init_tab(int tab[max]){
	for(int i = 0;i<max;i++)
		tab[i] = -1;
}

/*
Fonction : Cree un vaisseau avec les valeurs du fichiers name, en affectant son modele, sa signature et 0 tir au debut
Entree : chaine de caractere pour le nom d'un fichier vaisseau
Sortie : Vaisseau 
*/
Ship* create_ship(char* name, int tabVaisseau[max]){
	int fd = open(name,O_RDONLY);
	if(fd==-1){
		basic_message("Le vaisseau \"");
		basic_message(name);
		error_message("\" n'a pas été trouvé.");
	}

	char* buffer = malloc(BUFFER);
	char* bufferFgets = NULL; 
	Ship* vaisseau1 = (Ship*) malloc(sizeof(Ship));

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->taille_longueur = atoi(buffer);

	char* lettre = strpbrk(buffer," ");
	buffer = lettre+1;
	vaisseau1->taille_hauteur = atoi(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->pattern_cycle = atoi(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->deplacement = InitListe(vaisseau1->pattern_cycle);

	create_deplacementHorizontal(vaisseau1->deplacement,buffer,vaisseau1->pattern_cycle);
	myfgets(buffer,BUFFER,fd,bufferFgets);
	create_deplacementVertical(vaisseau1->deplacement,buffer,vaisseau1->pattern_cycle);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->point_vie = atoi(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->frequence_tir = atof(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->vitesse_tir = atoi(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->puissance_tir = atoi(buffer);

	myfgets(buffer,BUFFER,fd,bufferFgets);
	vaisseau1->symbole_tir = *buffer;

	vaisseau1->modele_vaisseau = atoi(name);

	vaisseau1->signature = curr_vaisseau;
	curr_vaisseau++;

	tabVaisseau[indice_vaisseau++] = vaisseau1->signature;

	vaisseau1->dernier_tir = 0;
	vaisseau1->nb_bombs = 1;
	close(fd);
	return vaisseau1;
}

/*
Fonction : Retourne la lettre du deplacement du pattern du vaisseau 
Entree : Vaisseau
Sortie : la lettre du deplacement
*/
char check_liste(Ship* v){
	char lettre = 'a';
	if(v->deplacement->deplacement_horizontal == 1) lettre = 'd';
	else if(v->deplacement->deplacement_horizontal == -1) lettre = 'q';
	else if(v->deplacement->deplacement_vertical == 1) lettre = 'z';
	else if(v->deplacement->deplacement_vertical == -1) lettre = 's';
	return lettre;
}

/*
Fonction : convertie l'entree des fleches en une lettre (pour le deplacement)
Entree : void 
Sortie : lettre
*/
char convert_arrow(){                                                                                                                                                                                        
	char buf[3];                                                                                                                                                               
	read(STDOUT_FILENO,buf,3);
	fflush(stdout);
	if(buf[0] == 27 && buf[1] == 91 ){
		switch(buf[2]){
			case 65 : return 'z';
			case 66 : return 's';   
			case 68 : return 'q';
			case 67 : return 'd';    
			default : return 0;     
		} 
	}else{
		switch(buf[0]){
			case 122 : return 'Z';
			case 115 : return 'S';   
			case 113 : return 'Q';
			case 100 : return 'D';
			case 98 : return 'B';    
			default : return 0;     
		}
	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
	return 0;                                                                                                                                                                            
}

/*
Fonction : cree un projectile pour un vaisseau  
Entree : un vaisseau
Sortie : Tir
*/
Tir* create_projectile(Ship* v, int power){
	Tir* t = (Tir*) malloc(sizeof(Tir));
	if(power == -1){
		t->signatureT = v->signature;
		t->degats = v->puissance_tir;
		t->vitesse = v->vitesse_tir;
		t->symboleT = v->symbole_tir;
		t->power_up = -1;
		
	}else if(power > 0){
		t->signatureT = 1;
		t->degats = 0;
		t->vitesse = 1;
		t->symboleT = '$';
		t->power_up = power;
		
	}
	return t;
}

/* Retourne la taille du tableau
*/
int taille_tab(int *tab){
	int compt = 0;
	for(int i = 0; i < max;i++)
		if(tab[i]!= -1)
			compt++;
		
	return compt;
}
/*
Fonction : Retourne si la valeur est dans le tableau de signaux   
Entree : tableau du jeu et le nouveau tableau du jeu
Sortie : int 1 true 0 false
*/
int contains(int signaVaisseaux[max], int val){
	for(int i= 0;i<max;i++) 
		if(signaVaisseaux[i] == val)
			return 1;
	
	return 0;
}

/*
Fonction : Effectue une pause en ms   
Entree : un temps en ms
Sortie : void
*/
void sleep_ms(int milliseconds){
	struct timespec ts;
	ts.tv_sec = milliseconds / 1000;
	ts.tv_nsec = (milliseconds % 1000) * 1000000;
	nanosleep(&ts, NULL);
}

/*
Fonction : Met le max de la variable globale max du descripteur fd 
Entree : descripteur de fichier
Sortie : void
*/
void get_max(int fd){
	char* buffer =malloc(BUFFER);
	char* bufferFgets = NULL;
	myfgets(buffer,BUFFER,fd,bufferFgets);
	max = atoi(buffer);
	free(buffer);
	free(bufferFgets);
}
/* Initialise les constantes
*/
void init_const(){
	curr_vaisseau = 0;
	max = 0;
	indice_vaisseau = 0;
}

/*
Fonction : Retourne si c'est un dossier 
Entree : poiteur vers la structure dirent 
Sortie : int
*/
int is_dir(struct dirent* dir){
	struct stat *fichier = malloc(sizeof(struct stat));
	if(stat(dir->d_name, fichier)==0)
		if(S_ISDIR(fichier->st_mode))
			return 1;
		
	return 0;
}

/*
Fonction : retourne l'etat du jeu   
Entree : tableau du jeu
Sortie : int 1 true 0 false
*/
int end(Space tab[LONGUEUR][LARGEUR]){
	if(max != curr_vaisseau) return 0;
	for(int i = 0;i<LONGUEUR;i++){
		for(int j = 0;j<LARGEUR;j++){
			if(tab[i][j].vaisseau!= NULL){
				if(tab[i][j].vaisseau->modele_vaisseau == 0) return 1;
				else if(tab[i][j].vaisseau->modele_vaisseau != 0) goto suite;
			}
		}
	}
	suite:for(int i = LONGUEUR - 1;i>=0;i--){
		for(int j = LARGEUR - 1;j>=0;j--){
			if(tab[i][j].vaisseau!= NULL){
				if(tab[i][j].vaisseau->modele_vaisseau == 0) return 0;
				else if(tab[i][j].vaisseau->modele_vaisseau > 0) break;
			}
		}
	}

	return 2;
}




/*
Fonction : Ouvre le fichier deroulement et execute les niveaux 
Entree : nom du dossier du mode
Sortie : void
*/
void get_games(char* buffer){

	if(chdir(buffer) == -2)
		error_message("Les vaisseaux sont perdus dans l'hyper-espace !\n");

	int fd = open("deroulement",O_RDONLY);
	if(fd==-1)
		error_message("Plan de bataille non trouvé !\n");

	int last_pos = lseek(fd, 0, SEEK_END);
	lseek(fd, 0, SEEK_SET);
	char* games = malloc(BUFFER);
	char* bufferFgets = NULL;
	chdir("niveaux");

	while(lseek(fd, 0, SEEK_CUR) != last_pos){
		myfgets(games, BUFFER, fd, bufferFgets);

		if(main_game(games)== -1)
			error_message("Vous avez échoué à défendre l'espace...\n");

		if(lseek(fd, 0, SEEK_CUR) != last_pos)
			basic_message("Direction le champ de bataille suivant !\n");
	}
	chdir("..");
	basic_message("Bravo ! Vous avez triomphé de tout vos ennemis, la galaxie est enfin sûre... Mais pour combien de temps? \n");
    restaure_terminal(&prev);
}

int get_first(Space tab[LONGUEUR][LARGEUR], int signa, int x_y){
	for(int i = 0;i<LONGUEUR;i++)
		for(int j = 0;j<LARGEUR;j++)
			if(tab[i][j].vaisseau != NULL && tab[i][j].vaisseau->signature == signa){
				if(x_y == 1) return i;
				else return j;
			}
	
	return -1;
}

int get_last(Space tab[LONGUEUR][LARGEUR], int signa, int x_y){
	for(int i = LONGUEUR-1;i>=0;i--)
		for(int j = LARGEUR-1;j>=0;j--)
			if(tab[i][j].vaisseau != NULL && tab[i][j].vaisseau->signature == signa){
				if(x_y == 1) return i;
				else return j;
			}
			
	return -1;
}