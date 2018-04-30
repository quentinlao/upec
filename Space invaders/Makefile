CC=gcc
CFLAGS=-W -Wall -g -std=gnu99
LDFLAGS=
EXEC=projet

all: $(EXEC)

projet: main.o manip.o space.o affichage.o
	$(CC) -o projet main.o manip.o space.o affichage.o $(LDFLAGS)

main.o: main.c 
	$(CC) -o main.o -c main.c  $(CFLAGS)

manip.o: manip.c manip.h
	$(CC) -o manip.o -c manip.c $(CFLAGS)

space.o: space.c space.h
	$(CC) -o space.o -c space.c $(CFLAGS)

affichage.o: affichage.c affichage.h
	$(CC) -o affichage.o -c affichage.c $(CFLAGS)
	
clean:
	rm -rf *.o
	rm -rf $(EXEC)