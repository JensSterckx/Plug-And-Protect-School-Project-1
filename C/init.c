
//gcc -o init init.c -l bcm2835

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
void cleanbcm();


int main(int argc, char **argv)
{
	FILE *fp;
	char tempchar[512];
	
	/*fp = fopen("config/init.config", "r");
	if(fp == NULL)
	{
		printf("error in opening complete.config");
		return -1;
	}
	else
	{
		fgets(tempchar, 512, fp);
		fclose(fp);
	}*/

	//General BCM error
	if (!bcm2835_init())
	{
		printf("BCM ERROR");
		return -1;
	}
	
	if(argc > 1) //Check if arguments are given
	{
		if(strcmp(argv[1], "clear") == 0 || strcmp(argv[1], "stop") == 0)
		{
			cleanbcm();
			printf("DONE");
		}
	}
	else
	{
		init();
		printf("DONE");
	}

	//Close all BCM connections
	//disabled for testing
	//cleanbcm();
	
	return 0;
}

/** INITIALIZATION **/
void init()
{
	void initleds();
	void inittemp();
	void initlight();

	//Inits
	inittemp();
	initlight();
	//Last, visual indicator
	initleds();
}

void initleds()
{
	/** LED PART **/
	// Set the pins to be an output for ERROR messaging
    bcm2835_gpio_fsel(PIN0, BCM2835_GPIO_FSEL_OUTP);
	bcm2835_gpio_fsel(PIN1, BCM2835_GPIO_FSEL_OUTP);
 	bcm2835_gpio_fsel(PIN2, BCM2835_GPIO_FSEL_OUTP);
 	bcm2835_gpio_fsel(PIN3, BCM2835_GPIO_FSEL_OUTP);
    bcm2835_gpio_fsel(PIN4, BCM2835_GPIO_FSEL_OUTP);
 	bcm2835_gpio_fsel(PIN5, BCM2835_GPIO_FSEL_OUTP);
 	bcm2835_gpio_fsel(PIN6, BCM2835_GPIO_FSEL_OUTP);
 	bcm2835_gpio_fsel(PIN7, BCM2835_GPIO_FSEL_OUTP);
	
	// Set all to high
	bcm2835_gpio_write(PIN0, HIGH);
	bcm2835_gpio_write(PIN1, HIGH);
	bcm2835_gpio_write(PIN2, HIGH);
	bcm2835_gpio_write(PIN3, HIGH);
	bcm2835_gpio_write(PIN4, HIGH);
	bcm2835_gpio_write(PIN5, HIGH);
	bcm2835_gpio_write(PIN6, HIGH);
	bcm2835_gpio_write(PIN7, HIGH);
	
	// wait a little bit
	bcm2835_delay(1000);
	
	bcm2835_gpio_write(PIN1, LOW);
	bcm2835_gpio_write(PIN2, LOW);
	bcm2835_gpio_write(PIN3, LOW);
	bcm2835_gpio_write(PIN4, LOW);
	bcm2835_gpio_write(PIN5, LOW);
	bcm2835_gpio_write(PIN6, LOW);
	bcm2835_gpio_write(PIN7, LOW);
}

void inittemp()
{
	char buffer[4];
	buffer[0] = 50;			

	/** TEMPERATURE PART **/
	bcm2835_spi_begin();
	bcm2835_spi_setBitOrder(BCM2835_SPI_BIT_ORDER_MSBFIRST);
	bcm2835_spi_setDataMode(BCM2835_SPI_MODE3);				//SCLK rising edge - clock idle state 1
	bcm2835_spi_setClockDivider(BCM2835_SPI_CLOCK_DIVIDER_65536); 	//set clock frequency
	bcm2835_spi_chipSelect(BCM2835_SPI_CS1);                      	//use chip select 1
	bcm2835_spi_setChipSelectPolarity(BCM2835_SPI_CS1, LOW);      	//chip select 0 to activate

	
	buffer[0]=buffer[1]=buffer[2]=buffer[3]=0;
	//bcm2835_spi_transfern(buffer,4);

	buffer[0] = 0x58;						//read the id
	bcm2835_spi_transfern(buffer,2);
	//printf("id:%02X\n",buffer[1]);
}

void initlight()
{
	char temp[1];				//temporary values

	bcm2835_i2c_begin();
	bcm2835_i2c_setSlaveAddress(0x29);      // addr pin attached to ground
	bcm2835_i2c_set_baudrate(1000);         // Default

	temp[0] = 0xa0;				//select the control register
	bcm2835_i2c_write(temp,1);
	temp[0] = 0x03;				//Power up the device
   	bcm2835_i2c_write(temp,1);
	bcm2835_delay(500);

	bcm2835_i2c_read(temp,1);
	//printf("%x - if 33 the device is turned on\n",temp[0]);
}

void cleanbcm()
{
	bcm2835_gpio_write(PIN0, HIGH);
	bcm2835_gpio_write(PIN1, LOW);
	bcm2835_gpio_write(PIN2, HIGH);
	bcm2835_gpio_write(PIN3, LOW);
	bcm2835_gpio_write(PIN4, HIGH);
	bcm2835_gpio_write(PIN5, LOW);
	bcm2835_gpio_write(PIN6, HIGH);
	bcm2835_gpio_write(PIN7, LOW);

	printf("\nClosing BCM\n");
	bcm2835_i2c_end();
	bcm2835_spi_end();
	bcm2835_close();
	printf("BCM Closed\n");
}

