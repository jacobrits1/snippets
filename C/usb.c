#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <fcntl.h>
#include <termios.h>

int fd;
int fd2;
int fdWrite;
int flags;
int rsl_len;
char result;
char buffer[1024];
int c;

int main (void)
{
  printf ("Running USB\n");
  flags = O_RDWR | O_NOCTTY; // Read and write, and make the job control for portability
  if ((fd = open("/dev/serial/by-id/usb-1a86_USB2.0-Ser_-if00-port0", flags)) == -1 ) {
    printf("Error while opening\n"); // Just if you want user interface error control
    return -1;
  }
  if ((fd2 = open("/dev/serial/by-id/usb-1a86_USB2.0-Ser_-if00-port0", flags)) == -1 ) {
    printf("Error while opening printer\n"); // Just if you want user interface error control
    return -1;
  }
  // In this point your communication is already estabilished, lets send out something
  printf("print slip\n");
   while(1)
   {
    ssize_t length = read(fd, &buffer, sizeof(buffer));
    if (length == -1)
    {
        printf("Error reading from serial port\n");
        break;
    }
    else if (length == 0)
    {
        printf("No more data\n");
        break;
    }
    printf("%s",buffer);
    FILE *log = fopen("logfile.txt", "at");
     if (!log) log = fopen("logfile.txt", "wt");
     if (!log) {
        printf("can not open logfile.txt for writing.\n");
        return 0;   // bail out if we can't log
     }
    fprintf(log,buffer);
    memset(&buffer[0], 0, sizeof(buffer));
      fclose(log);
      printf("got all serial");
      sleep(5);
      FILE *file = fopen("logfile.txt","r");
      if(file){
      while((c = getc(file)) != EOF){
       //write(fd2,&c,sizeof(c));
      }
      fclose(file);
      //remove("logfile.txt");
    }
    printf("Slip recieved");
    }
   close(fd);
   close(fd2);
   return 0;
  }
