
void afficheArg(char *argv[]){

  int i = 0;
  
  while(argv[i] != NULL){
    if(argv[i] == NULL){
      break;
    }
    printf("Arg : %d  = -%s-\n",i, argv[i]);
    
    
    i++;
  }

}

int cycle(char *s, char* tab[BUFFER]){
  int i = 0;
  printf("%s\n",s);
   char* lettre = "a";
    do{
      lettre = strpbrk(s," ");
      if(lettre != NULL){
         *lettre = '\0';
         tab[i++] = s;
         s = lettre+1;
      }else{
         lettre = strpbrk(s,"\n");
         if(lettre != NULL){
            *lettre = '\0';
            tab[i++] = s;
            lettre = NULL;
         }else i++;
      }
   } while(lettre != NULL);
   tab[i] = NULL;
  
  return 0;
}
/* exemple d'utilisation */
int main (){
  char *s = malloc(BUFFER);
  fgets(s,BUFFER,stdin);
  char* tab[BUFFER];
  cycle(s,tab);
  afficheArg(tab);
 
  struct termios prev;
  int nb,c;

  if(reconfigure_terminal(&prev)==-1)
      return 1;
  int tab[LONGUEUR][LARGEUR];
  //Init 0
  for(int i = 0;i<LONGUEUR;i++){
    for(int j = 0;j<LARGEUR;j++){
      tab[i][j] = 0;
    }
  }
  //Pos depart O(25,50)
  int a = 25;
  int b = 50;
  tab[a][b] = 1;
  //Affichage
  printTab(tab);
  sleep(5);
  //Ouverture fichier pattern
  int indice =0,nread;
  int fd = open("pattern",O_RDONLY);
  char buf[BUFFER]; 
  //boucle inf
  for(nb=0;;){
    tab[a][b]=0;
    //c=getchar();
    nb++;
    if(fd==-1){
      perror("pattern");
      
    }
    while(/*(nread = read(fd,buf,13))>0*/ 1){
       //espace
      if(buf ==33){
          //nread = read(fd,buf,1);
      }
      //S
      if(buf ==122){
          if(a-1 >= 0) a--;
      }
      //Z
      else if(buf==115){
          if(a+1 < LONGUEUR) a++;
      }
      //Q
      else if(buf==113){
          if(b-1 >= 0) b--;
      }
      //D
      else if(buf==100){
          if(b+1 < LARGEUR) b++;
      }
    }
   
    //DEL = ctrl+c
     if (c==127) {
        break;
    }  
    tab[a][b]=1;  
    //Affichage
    printTab(tab);
  }
  close(fd);
    //Reaff
    if(restaure_terminal(&prev)==-1)
      return 1;

    return 0;
  }