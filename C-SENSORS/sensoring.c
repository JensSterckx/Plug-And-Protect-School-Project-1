
//gcc -o sensoring sensoring.c -l bcm2835

#include <bcm2835.h>
#include <stdio.h>
#include <string.h>


//define the 8 leds
#define PIN0 RPI_V2_GPIO_P1_07		//4
#define PIN1 RPI_V2_GPIO_P1_08		//14
#define PIN2 RPI_V2_GPIO_P1_18		//24
#define PIN3 RPI_V2_GPIO_P1_16		//23
#define PIN4 RPI_V2_GPIO_P1_15		//22
#define PIN5 RPI_V2_GPIO_P1_13		//27
#define PIN6 RPI_V2_GPIO_P1_12		//18
#define PIN7 RPI_V2_GPIO_P1_11		//17

#define PIN40 RPI_BPLUS_GPIO_J8_40 	//21

void init();

int getTemperature();
int getLight();

void cleanbcm();

int main(int argc, char **argv)
{

	//General BCM error
	if (!bcm2835_init())
	{
		printf("BCM ERROR");
		return -1;
	}
	
	
	if(argc == 1)
	{
		//Normal run, nothing for the master
		printf("%d\n", getTemperature());
		printf("%d\n", getLight());
	}
	else
	{
		//clean data for the master
		if(strcmp(argv[1], "temperature") == 0)
		{
			printf("%d", getTemperature());
		}
		if(strcmp(argv[1], "light") == 0)
		{
			printf("%d", getLight());
		}
	}
	
	
	/*
	int i = 0;
	
	while(1)
	{
		printf("Req: %d\ntemp:%d\n", i, getTemperature());
		printf("light:%d\n", getLight());
		
		
		fp1 = fopen("data/temp", "w");
		fp2 = fopen("data/light", "w");
		if(fp1 == NULL || fp2 == NULL)
		{
			printf("error in opening data files");
			return -1;
		}
		else
		{
			fprintf(fp1, "%d", getTemperature());
			fprintf(fp2, "%d", getLight());
			fclose(fp1);
			fclose(fp2);
		}

		i++;
		bcm2835_gpio_write(PIN0, LOW);
		bcm2835_gpio_write(PIN1, HIGH);
		sleep(15);
		bcm2835_gpio_write(PIN0, HIGH);
		bcm2835_gpio_write(PIN1, LOW);
		sleep(15);
	}*/

	return 0;
}

/** INITIALIZATION **/


/** DATA **/
int getTemperature()
{
	bcm2835_gpio_write(PIN7, HIGH);
	
	char buffer[4];
	int i, temp;
	
	buffer[0] = 0x50;					//read the temp
	bcm2835_spi_transfern(buffer,3);
	//printf("status %02X %02X\n",buffer[1],buffer[2]);
	temp = buffer[1]; 
	temp = temp<<8;
    temp = temp + ( buffer[2] & 0xF8);
	//printf("status %08x\n",temp);
	temp = temp>>3;
	temp = temp/16;
	//printf("temp:%d\n",temp);
	
	bcm2835_gpio_write(PIN7, LOW);
	return temp;
}

int getLight()
{
	bcm2835_gpio_write(PIN6, HIGH);
	
	char temp[1];
	int ad[2];

	temp[0] = 0xac;				//Channel 0 lower byte
    bcm2835_i2c_write(temp,1);		
	bcm2835_i2c_read(temp,1);

	ad[1]= (int)temp[0];

    temp[0] = 0xad;				//channel 0 upper byte
    bcm2835_i2c_write(temp,1);
    bcm2835_i2c_read(temp,1); 

	ad[0] = (int)temp[0];
	//printf("ad value:%d\n",ad[0]*256+ad[1]);
	
	bcm2835_gpio_write(PIN6, LOW);
	return ad[0]*256+ad[1];
}

