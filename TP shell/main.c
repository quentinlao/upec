#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/stat.h>
#include <sys/errno.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <signal.h>
#define BUFFSIZE 2048

int entree;
int sortie;

/*
Fonction : Execute un script 
Entree : nom du script
Sortie : void
*/
int parse_line(char *s);
void script(char *sc){
	FILE* fp = fopen(sc,"r");
	char buffer[BUFFSIZE];
	char *c = NULL;
	if(fp == NULL) return;
    else{
    	while(!feof(fp)){
    			c  = fgets(buffer,BUFFSIZE,fp);
    			printf("%s\n",c);
    			parse_line(c);
    		}			
	}
	return;
}

/*
Fonction : Execute les redirections entree sortie 
Entree : commande, entree, sortie
Sortie : int
*/
int simple_cmd(char *argv[]);
int redir_cmd(char *argv[],char *in, char *out){
  	if(out != NULL){
	    int fd = open(out,O_WRONLY);
	    dup2(fd,STDOUT_FILENO);
	  	close(fd);
	}
  	if(in !=NULL){
	  	int fd = open(in,O_RDONLY);
	    dup2(fd,STDIN_FILENO);
	  	close(fd);
  	}
  	simple_cmd(argv);
  	dup2(sortie,STDOUT_FILENO);
  	dup2(entree,STDIN_FILENO); 
    return 0;
}

/*
Fonction : Execute la commande 
Entree : commande
Sortie : int
*/
int simple_cmd(char *argv[]){
	if(strcmp(argv[0], "exit") == 0) exit(0);
	if(strcmp(argv[0], "cd") == 0){
			if(argv[1] ==  NULL) chdir("/home");
			else chdir(argv[1]);
	}
	else{
		pid_t pid = fork();
		if(pid == -1){
			perror("pid");
			exit(0);
		}
		else if (pid  == 0) {	
			execvp(argv[0], argv);
			perror("excvp");
			exit(0);
		}
		else wait(0);	
	}
	return 0;
}

/*
Fonction : Execute la premiere commande  
Entree : commande, entree
Sortie : int
*/
int start_cmd(char* argv[],char* in){
  int fd = open(in,O_RDONLY);
  dup2(fd,STDIN_FILENO);
  close(fd);
  execvp(argv[0],argv);
  return 0;
}

/*
Fonction : Execute la commande(suivante) 
Entree : commande
Sortie : int
*/
int next_cmd(char* argv[]){
  int i = 0;
  while(argv[i] != NULL){
	  execvp(argv[i],argv);
	  i++;
  }
  return 0;
}

/*
Fonction : Execute la dernire commande 
Entree : commande, sortie
Sortie : int
*/
int last_cmd(char* argv[],char* out){
  int fd = open(out,O_WRONLY);
  dup2(fd,STDOUT_FILENO);
  close(fd);
  execvp(argv[0],argv);
  return 0;
}

/*
Fonction : Transforme la chaine et execute simple_cmd 
Entree : chaine
Sortie : int
*/
int parse_line(char *s){
	if(strcmp(s,"\n")==0)	return 0;
	char *space = strpbrk(s," ");
	char *jmp = strpbrk(s,"\n");
	char *hashtag = strpbrk(s,"#");
	char *equal = strpbrk(s,"=");
	char *dol = strpbrk(s,"$");
	char *entree = strpbrk(s,"<");
    char *sortie = strpbrk(s,">");
	char *buf[BUFFSIZE];
	int i = 0;
	if(hashtag != NULL) *hashtag = '\0';
	//----------------------------------------------------------------------
	if(sortie != NULL && entree != NULL){
		//ON RECUPERE LA SORTIE
		if(space != NULL){
			*sortie = '\0';
			sortie++;
			space = strpbrk(sortie," ");
			while(space != NULL){
				*space ='\0';
				sortie = (space+1);
				space = strpbrk(sortie," ");
			}
			if(jmp != NULL) *jmp = '\0';
		}
		//ON RECUPERE L'ENTREE
		space = strpbrk(entree," ");
		if(space != NULL){
			*entree = '\0';
			entree++;
			while(space != NULL){
				*space ='\0';
				entree = (space+1);
				if(!((entree - 2)!= NULL))
				space = strpbrk(entree," ");
				else break;//On s'arrete a l'avant dernier cas
			}
			//On enleve l'espace de fin
			space = strpbrk(entree," ");
			*space ='\0';				
		}
		i  = 0;
		space = strpbrk(s," ");
		while(s != NULL){
			if(space != NULL){
				if(s == space){
					*space ='\0';
					s = (space+1);
					space = strpbrk(s," ");
				}
				else{
					*space ='\0';
					buf[i]=s;		
					s = (space+1);
					space = strpbrk(s," ");
					i++;
				}
			}
			else s = NULL;
		}
		buf[i] =  NULL;
		s = NULL;
		int j = 0;
		while(buf[j] != NULL){
			j++;
		}
		redir_cmd(buf,entree,sortie);
	}
	else if(sortie != NULL){
		// RECUPERE LA SORTIE SANS LES ESPACES
		if(space != NULL){
			*sortie = '\0';
			sortie++;
			space = strpbrk(sortie," ");
			while(space != NULL){
				*space ='\0';
				sortie = (space+1);
				space = strpbrk(sortie," ");
			}
			if(jmp != NULL) *jmp = '\0';
		}
		//RECUPERE LA COMMANDE COMME PARSE LINE SANS ESPACE
		i  = 0;
		space = strpbrk(s," ");
		while(s != NULL){
			if(space != NULL){
				if(s == space){
					*space ='\0';
					s = (space+1);
					space = strpbrk(s," ");
				}
				else{
					*space ='\0';
					buf[i]=s;		
					s = (space+1);
					space = strpbrk(s," ");
					i++;
				}
			}
			else s = NULL;
		}
		buf[i] =  NULL;
		s = NULL;
			int j = 0;
		while(buf[j] != NULL){
			printf("Arg : %d  = -%s-\n",j, buf[j]);
			j++;
		}
		redir_cmd(buf,entree,sortie);
	}	
	//---------------------------------------------------
	while(s != NULL){
		if(space != NULL){
			if(s == space){
				*space ='\0';
				s = (space+1);
				space = strpbrk(s," ");
			}
			else{
				*space ='\0';
				buf[i]=s;		
				s = (space+1);
				space = strpbrk(s," ");
				i++;
			}
		}
		else if(jmp != NULL){		
			*jmp = '\0';
			buf[i] = s;
			i++;
			buf[i] = NULL;
			jmp = strpbrk(s,"\n");	
		}
		else if(equal != NULL){
			*equal ='\0';
			char *donne= s;
			char *val = equal + 1;
			//printf("donne : %s, val : %s\n ",donne,val);
			setenv(donne,val,3);
			equal = strpbrk(s++,"=");
		}
		else if(dol != NULL){
			dol++;
			buf[i] = getenv(dol);
			dol = strpbrk(s++,"$");
		}
		else s = NULL;
	}
	simple_cmd(buf);
	return 0;
}

static void handler(){
    char *chaine="Bloque signal ctrl+c\n";
	write(STDERR_FILENO, chaine, strlen(chaine));
}

int main(int argc, char *argv[]){
	//Je bloque le signal
	struct sigaction act;
	act.sa_handler = &handler;
    act.sa_flags =SA_RESTART;
	sigaction(SIGINT, &act, NULL);
	//Je garde l'entree et la sortie pour le remettre apres la redirection
	entree = dup(STDIN_FILENO);
	sortie = dup(STDOUT_FILENO);
	char courant[100];
	if(argv[1]!=NULL && argc > 1) script(argv[1]);
	else{
		while(1){
			getcwd(courant, 100);
			printf("%s$ ",courant);
			char buf[1024];
			fgets(buf,sizeof(buf),stdin); 	
			if(*buf == '#'){
				printf("%s$ ",courant);
				fgets(buf,sizeof(buf),stdin);
			}
			parse_line(buf);		
		}
	}
	return 0;
}