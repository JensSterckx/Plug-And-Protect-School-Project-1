
//gcc -o alarm_gpio alarm_gpio.c -l bcm2835

#include <bcm2835.h>
//#include <mysql.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <stdlib.h>



#define PIN40 RPI_BPLUS_GPIO_J8_40   		//21?
//#define PIN40 RPI_V2_GPIO_P1_13 

void init();

void startAlarm();
void stopAlarm();

int main(int argc, char **argv)
{

	//General BCM error
	if (!bcm2835_init())
	{
		printf("BCM error");
		return -1;
	}
	
	if (argc < 2)
	{
		printf("no/bad arguments\n\n");
		return -1;
	}
	
	init();
	
	if (strcmp(argv[1], "1") != -1)
	{
		FILE *pidfile;

		pidfile = fopen("/tmp/alarm.pid", "r");
		if(!(pidfile == NULL))
		{
			printf("IS ON");
			return -1;
		}
		//sleep(1);
		
		startAlarm();
	}
	else
	{
		stopAlarm();
	}
	
	return 0;
}

/** INITIALIZATION **/
void init()
{
	bcm2835_gpio_fsel(PIN40, BCM2835_GPIO_FSEL_OUTP);
}

void startAlarm()
{
	FILE *pidfile;
	int pid;
	
	pidfile = fopen("/tmp/alarm.pid", "w");
	fprintf(pidfile, "%d", getpid());
	fclose(pidfile);

	
	// Set alarm to high
	while(1)
	{
		bcm2835_gpio_write(PIN40, HIGH);
		//printf("TUUT");
		// wait a little bits
		
		bcm2835_delay(1000);
		bcm2835_gpio_write(PIN40, LOW);
		bcm2835_delay(500);

		pidfile = fopen("/tmp/alarm.pid", "r");
		if(pidfile == NULL)
		{
			//File was removed, so stop program
			exit(1);			
		}
		else
		{
			fscanf(pidfile, "%d%*c", &pid);
			fclose(pidfile);
			if(pid != getpid())
			{
				//Wrong PID
				exit(1);
			}
		}
	}
}

void stopAlarm()
{
	//remove file
	unlink("/tmp/alarm.pid");
	bcm2835_gpio_write(PIN40, LOW);
}
