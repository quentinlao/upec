
#include "terminal.h"
/* cette fonction reconfigure le terminal, et stocke */
/* la configuration initiale a l'adresse prev */
int reconfigure_terminal (struct termios *prev){
  struct termios new;
  //Verif Recuperation 
  if(tcgetattr(STDIN_FILENO,prev)==-1){
      perror("tcgetattr");
      return -1;
  }
  //flag
  new.c_iflag=prev->c_iflag;
  new.c_oflag=prev->c_oflag;
  new.c_cflag=prev->c_cflag;
  new.c_lflag=0;
  new.c_cc[VMIN]=0;
  new.c_cc[VTIME]=0;
  new.c_cc[VINTR]=0;
  if(tcsetattr(STDIN_FILENO,TCSANOW,&new)==-1) {
      perror("tcsetattr");
      return -1;
  }
  return 0;
}

/* cette fonction restaure le terminal avec la */
/* configuration stockee a l'adresse prev */
int restaure_terminal (struct termios *prev){
    return tcsetattr(STDIN_FILENO,TCSANOW,prev);
}