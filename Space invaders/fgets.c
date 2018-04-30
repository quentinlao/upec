
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <string.h>
#define BUFFSIZE 2048

//char* volatile bufferFgets = NULL;

void myfgets(char* buffer, int length, int fd, char* bufferFgets){
	if(length > 0 || strlen(buffer) > 0){
		if(bufferFgets == NULL){
			bufferFgets = malloc(length);
			read(fd,bufferFgets,length);
			//printf("%s\n",bufferFgets);
		}

		char* lettre = strpbrk(bufferFgets,"\n");
		if(lettre != NULL){
			/**lettre = '\0';
			int difference = nread - strlen(bufferFgets);
			printf("diff = %d\n",difference);
			strcpy(buffer,bufferFgets);
			printf("lseek = %ld\n",lseek(fd, (off_t) (difference*-1),SEEK_CUR));
			bufferFgets = realloc(bufferFgets, strlen(bufferFgets));
			bufferFgets = realloc(bufferFgets, strlen(bufferFgets) + difference + BUFFSIZE);
			printf("old strlen = %ld\n",strlen(bufferFgets));
			bufferFgets = lettre + 1;
			printf("new strlen = %ld\n\n\n",strlen(bufferFgets));*/
			int taille = strlen(lettre);
			*lettre = '\0';
			strcpy(buffer, bufferFgets);
			bufferFgets = lettre + 1;
			//printf("%d\n", (int) strlen(lettre));
			lseek(fd, -1*taille + 1, SEEK_CUR);

			
		}else if(lettre == NULL){
			int taille = strlen(bufferFgets);
			strcpy(buffer,bufferFgets);
			char* newP = buffer + taille;
			if(taille != length) lseek(fd, taille, SEEK_CUR);
			bufferFgets = NULL;
			myfgets(newP,length - taille,fd, bufferFgets);

			 	/*if(strlen(bufferFgets)==length){
			 		strcpy(buffer,bufferFgets);
			 		lseek(fd,length,SEEK_CUR);
			 		bufferFgets = NULL;
			 	}
			 	else{

			 		strcpy(buffer,bufferFgets);
			 		int difference = length - strlen(bufferFgets);
			 		char* newP = buffer + strlen(buffer);
			 		lseek(fd,difference,SEEK_CUR);
			 		bufferFgets = realloc(bufferFgets, difference + strlen(bufferFgets));
			 		bufferFgets = bufferFgets + strlen(bufferFgets);
			 		read(fd, bufferFgets, difference);
			 		myfgets(newP, length, fd);
			 	}*/
			
			
		}
	}
}

int main(){
	int fd = open("text",O_RDONLY);
	char* buffer = malloc(BUFFSIZE);
	char* bufferFgets = NULL;
	myfgets(buffer,BUFFSIZE,fd,bufferFgets);
	printf("%s\n\n\n",buffer);
	myfgets(buffer,BUFFSIZE,fd,bufferFgets);
	printf("\n%s\n",buffer);
	return 0;
}
