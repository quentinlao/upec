#ifndef TERMINAL 
#define TERMINAL 

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


#define NOOPTION ((option_t*)NULL)
int reconfigure_terminal (struct termios *prev);
int restaure_terminal (struct termios *prev);
#endif