/*
PRPR Projekt 2
Filip Remšík
*/
#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
//zadefinovanie struktury
typedef struct zver{
	char meno[50];
	char druh[30];
	char vyska[10];
	char vaha[15];
	char narodenie[10];
	char krmenie[10];
	char osetrovatel[50];
	struct zver* dalsi;
}zvierata;
//nacitanie zo suboru------------------------------------------------------
zvierata* prikaz_n(zvierata*zozn,int* pocet) {
	FILE* fr;
	zvierata* pridaj,*akt=NULL;
	int i = 0, j = 0;
	char c[50];
	if ((fr=fopen("zvierata.txt","r"))==NULL) {
		printf("Zaznamy neboli nacitane");
		exit(0);
	}
	//uvolnenie pamäte ak existuje
	if (zozn != NULL) {
		while (zozn != NULL) {
			akt = zozn;
			zozn = zozn->dalsi;
			free(akt);
		}
	}
	fgets(c, 50, fr);
	pridaj = (zvierata*)malloc(sizeof(zvierata));
	while ((fgets(c, 50, fr)) != NULL) {
		i++;
		strtok(c,"\n");
		if (i == 1) {
			j++;
			strcpy(pridaj->meno,c);
		}
		if (i == 2) {
			strcpy(pridaj->druh,c);
		}
		if (i == 3) {
			strcpy(pridaj->vyska,c);
		}
		if (i == 4) {
			strcpy(pridaj->vaha,c);
		}
		if (i == 5) {
			strcpy(pridaj->narodenie, c);
		}
		if (i == 6) {
			strcpy(pridaj->krmenie, c);
		}
		if (i == 7) {
			strcpy(pridaj->osetrovatel, c);
		}
		if (i == 8) {
			i = 0;
			if (j == 1) {
				zozn = pridaj;
			}
			pridaj->dalsi = NULL;
			pridaj->dalsi= (zvierata*)malloc(sizeof(zvierata));
			pridaj = pridaj->dalsi;
			pridaj->dalsi = NULL;
		}
	}
	//priradenie posledneho zoznamu
	pridaj->dalsi = NULL;
	pridaj->dalsi = (zvierata*)malloc(sizeof(zvierata));
	pridaj = pridaj->dalsi;
	pridaj->dalsi = NULL;
	*pocet = j;
	printf("Nacitalo sa %d zaznamov\n",j);
	return(zozn);
}
//vypisanie spajaneho zoznamu----------------------------------------------
void prikaz_v(zvierata *zozn) {
	zvierata* akt=NULL;
	int i = 1;
	akt = zozn;
	if (zozn==NULL) {
		return(0);
	}
	do{
		printf("%d.\n",i);
		printf("meno: %s\n", akt->meno);
		printf("druh: %s\n", akt->druh);
		printf("vyska: %s\n", akt->vyska);
		printf("vaha: %s\n", akt->vaha);
		printf("datum: %s\n", akt->narodenie);
		printf("datum krmenia: %s\n", akt->krmenie);
		printf("osetrovatel: %s\n", akt->osetrovatel);
		if (akt->dalsi != NULL) {
			akt = akt->dalsi;
		}
		i++;
	
	} while (akt->dalsi != NULL);

}
//pridanie zvierata do zoznamu---------------------------------------------
zvierata* prikaz_p(zvierata*zozn,int pocet) {
	int i,j=2;
	char c[50];
	zvierata* nacitaj,*akt=NULL;
	akt = zozn;
	nacitaj=(zvierata*)malloc(sizeof(zvierata));
	printf("Zadaj vstupne udaje\n");
	printf("Pozicia\n");
	scanf("%d",&i);
	getchar();
	printf("meno\n");
	fgets(c,50,stdin);
	strtok(c, "\n");
	strcpy(nacitaj->meno,c);
	printf("druh\n");
	fgets(c, 50, stdin);
	strtok(c, "\n");
	strcpy(nacitaj->druh, c);
	printf("vyska\n");
	scanf("%s", nacitaj->vyska);
	printf("vaha\n");
	scanf("%s", nacitaj->vaha);
	printf("datum narodenia\n");
	scanf("%s", nacitaj->narodenie);
	printf("posledne krmenie\n");
	scanf("%s", nacitaj->krmenie);
	getchar();
	printf("osetrovatel\n");
	fgets(c, 50, stdin);
	strtok(c, "\n");
	strcpy(nacitaj->osetrovatel, c);
	//priradenie do zoznamu
	nacitaj->dalsi = NULL;
	if (zozn == NULL) {
		zozn = nacitaj;
	}
	else if (i==1) {
		nacitaj->dalsi = zozn;
		zozn = nacitaj;
	}
	else if (i<=pocet) {
		while (akt->dalsi != NULL) {
			if (i==j) {
				nacitaj->dalsi = akt->dalsi;
				akt->dalsi = nacitaj;
			}
			akt = akt->dalsi;
			j++;
		}

	}
	else {
		while (akt->dalsi != NULL) {
			akt = akt->dalsi;
		}
		akt->dalsi = nacitaj;
	}
	return(zozn);
}
//vymazanie zvierata zo zoznamu--------------------------------------------
zvierata* prikaz_z(zvierata*zozn) {
	zvierata* predch=NULL, * akt=NULL;
	char c[50],d[50];
	akt = zozn;
	printf("Zadaj meno zvierata\n");
	fgets(c, 50, stdin);
	strtok(c,"\n");
	for (int i = 0;i < strlen(c);i++) {
		c[i] = tolower(c[i]);
	}
	printf("%s",c);
	//hladanie mena v zozname
	while (akt!=NULL) {
		strcpy(d,akt->meno);
		for (int i = 0;i < strlen(d);i++) {
			d[i] = tolower(d[i]);
		}
		if (strcmp(c,d)==0) {
			break;
		}
		predch = akt;
		akt = akt->dalsi;
	}
	//ak bolo meno v zozname vymaz ho
	if (akt != NULL) {
		if (akt == zozn) {
			zozn = zozn->dalsi;
		}
		else {
			predch->dalsi = akt->dalsi;
		}
		free(akt);
		printf("Zviera s menom %s bolo vymazane.\n",c);
	}
	if (akt == NULL) {
		printf("Zviera s menom %s nie je v zozname.\n", c);
	}
	return(zozn);

}
//vypis nenakrmenych zvierat-----------------------------------------------
void prikaz_h(zvierata*zozn) {
	zvierata* akt = NULL;
	long int datum;
	int i = 1,n=0;
	akt = zozn;
	if (zozn == NULL) {
		printf("zoznam neexistuje\n");
		return(0);
	}
	printf("Zadaj datum krmenia\n");
	scanf("%ld",&datum);
	getchar();
	while (akt->dalsi != NULL) {
		//porovnanie datumov
		if (datum > atol(akt->krmenie)) {
			n++;
			printf("%d.\n", n);
			printf("meno: %s\n", akt->meno);
			printf("druh: %s\n", akt->druh);
			printf("vyska: %s\n", akt->vyska);
			printf("vaha: %s\n", akt->vaha);
			printf("datum: %s\n", akt->narodenie);
			printf("datum krmenia: %s\n", akt->krmenie);
			printf("osetrovatel: %s\n", akt->osetrovatel);
		}
		akt = akt->dalsi;
	}
	if (n==0) {
		printf("Vsetky zvierata boli k datumu %ld nakrmene.\n",datum);
	}

}
//aktualizovanie datumu krmenia--------------------------------------------
void prikaz_a(zvierata*zozn) {
	zvierata* akt = NULL;
	char m[50],rok[10];
	akt = zozn;
	if (zozn == NULL) {
		printf("zoznam neexistuje\n");
		return(0);
	}
	printf("Zadaj meno zvierata\n");
	fgets(m, 50, stdin);
	strtok(m, "\n");
	printf("Zadaj datum krmenia\n");
	fgets(rok, 10, stdin);
	strtok(rok, "\n");
	//najdenie zaznamu a zmena udaju
	while (akt->dalsi != NULL) {
		if (strcmp(akt->meno, m) == 0) {
			strcpy(akt->krmenie, rok);
			printf("Zviera s menom %s bolo naposledy nakrmene dna %s.\n",m,rok);
			break;
		}
		
		akt = akt->dalsi;
	}

}
int main (){
	zvierata* zoznam = NULL,*akt=NULL;
	int pocet=0;
	char c;
	do {
		printf("Zadaj prikaz\n");
		scanf("%c",&c);
		getchar();
		if (c == 'n') {
			zoznam = prikaz_n(zoznam,&pocet);
		};
		if (c=='v') {
			prikaz_v(zoznam);
		};
		if (c == 'p') {
			zoznam=prikaz_p(zoznam,pocet);
		};
		if (c == 'z') {
			zoznam=prikaz_z(zoznam);
		};
		if (c == 'h') {
			prikaz_h(zoznam);
		};
		if (c == 'a') {
			prikaz_a(zoznam);
		};

	} while (c!='k');
	if (zoznam!=NULL) {
		while (zoznam != NULL) {
			akt = zoznam;
			zoznam = zoznam->dalsi;
			free(akt);
		}
		
	}
}