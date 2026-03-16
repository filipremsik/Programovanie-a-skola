/*
PrPrProjekt1
Filip Remšík
*/
#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
//po stlaceni v --------------------------------------------
FILE* prikaz_v() {
	FILE* fr;
	char *c,*p;
	c = calloc(50, sizeof(char));
	int i = 0;
	if ((fr = fopen("pacienti.txt", "r")) == NULL){
	printf("Neotvoreny subor\n");
	exit(0);
}
	//vrátenie na zaèiatok suboru
	fseek(fr, 0, SEEK_SET);
	while ((fgets(c,"\n",fr)) != NULL) {
		i++;
		strtok(c, "\n");
		switch (i) {
		case 1:
			if (strlen(c) > 50) {
				printf("Meno a priezvisko je moc dlhe");
				exit(0);
			}
			printf("meno priezvisko: %s\n", c);
			break;
		case 2:
			if (strlen(c)!=10) {
				printf("Rodne cislo nema 10 miest");
				exit(0);
			}
			if ((atoll(c))%11!=0) {
				printf("Rodne cislo nie je delitelne 11");
				exit(0);
			}
			printf("rodne cislo: %s\n", c);
			break;
		case 3:
			printf("diagnoza: %s\n", c);
			break;
		case 4:
			if (strlen(c) > 50) {
				printf("Vysetrenie je moc dlhe");
				exit(0);
			}
			printf("vysetrenie: %s\n", c);
			break;
		case 5:
			if ((strtod(c,&p)>1000)|| (strtod(c, &p) < 0)) {
				printf("Vysledok nie je z intervalu\n");
				exit(0);
			}
			printf("vysledok: %s\n", c);
			break;
		case 6:
			if (strlen(c) != 8) {
				printf("Datum nema 8 miest");
				exit(0);
			}
			printf("datum: %s\n", c);
			break;
		case 7:
			i = 0;
			printf("\n");
			break;
		}
	}
	free(c);
	//odkaz na subor
	return(fr);
}
//po stlaceni n-----------------------------------------------
void prikaz_n(char ***meno_priezvisko,char***rodne_cislo, char***diagnoza, char***vysetrenie, char***vysledok, char***datum, int* zoznam, FILE* fr) {
	char *c;
	c = calloc(50,sizeof(char));
	int i = 0,j = 0;
	if (fr == NULL) {
		printf("Subor nebol otvorevy vo v\n");
		return(0);
	}
	else {
		fseek(fr, 0, SEEK_SET);
		while ((fgets(c, "\n", fr)) != NULL) {
			i++;
		}
		*zoznam = ((i/7)+1);
	}
	if (*meno_priezvisko != NULL) {
		free(*meno_priezvisko);
		free(*rodne_cislo);
		free(*diagnoza);
		free(*vysetrenie);
		free(*vysledok);
		free(*datum);
	}
	//alokacia 1d poli s odkazom
	(*meno_priezvisko) = calloc(*zoznam, sizeof(char*));
	(*rodne_cislo) = calloc(*zoznam, sizeof(char*));
	(*diagnoza) = calloc(*zoznam, sizeof(char*));
	(*vysetrenie) = calloc(*zoznam, sizeof(char*));
	(*vysledok) = calloc(*zoznam, sizeof(char*));
	(*datum) = calloc(*zoznam, sizeof(char*));
	//alokacia stringu
	for (i = 0; i < *zoznam;i++) {
		(*meno_priezvisko)[i] = calloc(50, sizeof(char));
		(*rodne_cislo)[i] = calloc(10, sizeof(char));
		(*diagnoza)[i] = calloc(10, sizeof(char));
		(*vysetrenie)[i] = calloc(50, sizeof(char));
		(*vysledok)[i] = calloc(10, sizeof(char));
		(*datum)[i] = calloc(10, sizeof(char));
	}
	fseek(fr, 0, SEEK_SET);
	i = 0;
	//priradenie suboru do poli
	while ((fgets(c,50, fr)) != NULL) {
		strtok(c, "\n");
		i++;
		if (i == 1) {
			strcpy((*meno_priezvisko)[j],c);
		}
		if (i == 2) {
			strcpy((*rodne_cislo)[j],c);
		}
		if (i == 3) {
			strcpy((*diagnoza)[j],c);
		}
		if (i == 4) {
			strcpy((*vysetrenie)[j], c);
		}
		if (i == 5) {
			strcpy((*vysledok)[j],c);
		}
		if (i == 6) {
			strcpy((*datum)[j],c);
		}
		if(i==7){
			i = 0; 
			j++;
		}
	}
	
	i = 0;
}
//po stlaceni s-----------------------------------------------
void prikaz_s(char **rodne_cislo,char**vysetrenie,char**vysledok,int zoznam) {
	char c[50];
	int i = 0;
	if (rodne_cislo == NULL) {
		printf("Polia nie su vytvorene\n");
		return;
	}
	//nacitanie r.c. a porovnanie s polami
	printf("Zadaj rodne cislo\n");
	scanf("%s",&c);
	for (i;i < zoznam;i++) {
		if (strcmp(rodne_cislo[i],c)==0) {
			printf("%s: %s\n",vysetrenie[i],vysledok[i]);
			break;
		}
	}

}
//po stlaceni o-----------------------------------------------
int prikaz_o(FILE*fr,int zoznam) {
	long int rok,*pocetnost;
	int i=0,poc=0,poz=0,max=0;
	char* c,*d,**diag;
	if (fr == NULL) {
		printf("Subor nebol otvorevy vo v\n");
		return(0);
	}
	//alokacia
	c = calloc(50, sizeof(char));
	d = calloc(10, sizeof(char));
	diag = calloc(zoznam, sizeof(char*));
	pocetnost = calloc(zoznam, sizeof(int));
	for (i;i < zoznam;i++) {
		diag[i] = calloc(10,sizeof(char));
		strcpy(diag[i],"");
	}
	i = 0;
	printf("Zadaj rok\n");
	scanf("%ld",&rok);
	fseek(fr, 0, SEEK_SET);
	//rozdelenie diagnoz a ziskanie pocetnosti
	while ((fgets(c, "\n", fr)) != NULL) {
		i++;
		strtok(c, "\n");
		if (i == 3) {
			strcpy(d,c);
		}
		if ((i==6) && (rok > atol(c))) {
			for (int j = 0;j < zoznam;j++) {
				if (strcmp(diag[j],d)==0){
					pocetnost[j]++;
					break;
				}
				if ((strcmp(diag[j], d) != 0) && (strcmp(diag[j], "") == 0)) {
					pocetnost[j]++;
					strcpy(diag[j],d);
					poc++;
					break;
				}
			}
		}
		if (i == 7) {
			i = 0;
		}
	}
	//najdenie max pocetnosti a pozicie
	i = 0;
	max = pocetnost[0];
	for (i; i < poc;i++) {
		if (max < pocetnost[i]) {
			poz=i;
			max = pocetnost[i];
		}
		
	}
	printf("Najcastejsie vysetrovana diagnoza do %ld je %s.\n",rok,diag[poz]);
	free(diag);
	free(pocetnost);
	free(c);
	free(d);
}
//po stlaceni h----------------------------------------------
int prikaz_h(char**diagnoza,char**rodne_cislo,int zoznam){
	char c[50];
	char* p;
	int i = 0,vek,pohl,mesiac,den,*muzi,*zeny;
	long long int rok;

	if (diagnoza == NULL) {
		printf("Polia nie su vytvorene\n");
		return;
	}
	//zistenie veku(podla datumu 12.11.2020)+rozdelenie pohlavi
	muzi = calloc(100, sizeof(int));
	zeny = calloc(100, sizeof(int));
	printf("Zadaj diagnozu\n");
	scanf("%s",&c);
	for (i; i < zoznam; i++) {
		if (strcmp(diagnoza[i],c) == 0) {
			rok=strtoll(rodne_cislo[i],&p,10);
			rok=rok/10000;
			den = rok % 100;
			rok = rok / 100;
			mesiac = rok % 100;
			rok = rok/10;
			pohl = rok % 10;
			rok = rok / 10;
			vek = 20 - rok;
			if (vek < 0) {
				vek = vek + 100;
			}
			if (mesiac>12) {
				mesiac = mesiac - 50;
			}
			if ((den>12)&&(mesiac>=11)) {
				vek = vek - 1;
			}
			if (pohl > 2) {
				zeny[vek]++;
			}
			if (pohl < 2) {
				muzi[vek]++;
			}
		
		}
	}
	//vypis
	i = 0;
	printf("Muzi\n");
	for (i;i < 100;i++) {
		if (muzi[i] > 0) {
			printf("%d: %d\n",i,muzi[i]);
		}
	}
	i = 0;
	printf("Zeny\n");
	for (i;i < 100;i++) {
		if (zeny[i] > 0) {
			printf("%d: %d\n", i, zeny[i]);
		}
	}
	free(muzi);
	free(zeny);

}
//po stlaceni z-----------------------------------------------
void prikaz_z(char** vysetrenie, char** vysledok, char** meno_priezvisko, char** datum,int zoznam) {
	long int r1, r2,r;
	double* vysledky;
	int i = 0,*pozicia,pocet=0,max=0;
	char c[50],*p;
	if (vysetrenie == NULL) {
		printf("Polia nie su vytvorene\n");
		return;
	}
	printf("1. Datum\n");
	scanf("%ld",&r1);
	printf("2. Datum\n");
	scanf("%ld",&r2);
	printf("Vysetrenie\n");
	scanf("%s",&c);
	if (r1 > r2) {
		printf("Koncovy datum sa nachadza pred pociatocnym datumom,datumy budu vymenene.\n");
		r = r1;
		r1 = r2;
		r2 = r;
	}
	vysledky = calloc(zoznam, sizeof(double));
	pozicia = calloc(zoznam, sizeof(int));
	//overenie udajov
	for (i;i < zoznam;i++) {
		if ((r2 > atol(datum[i])) && (r1 < atol(datum[i])) && (strcmp(vysetrenie[i],c)==0)) {
			pozicia[pocet] = i;
			vysledky[pocet] = strtod(vysledok[i], &p);
			pocet++;
		}
	}
	//vypis ak su aspon 3
	if (pocet >= 3) {
		for (int j = 0;j < 3;j++) {
			max = 0;
			for (i = 1;i < pocet;i++) {
				if (vysledky[max] < vysledky[i]) {
					max = i;
				}
			}
			printf("%s %g\n", meno_priezvisko[pozicia[max]], vysledky[max]);
			vysledky[max] = 0;
		}

	}
	//vypis pre menej ako 3
	if (pocet < 3) {
		for (i = 0;i < pocet;i++) {
			printf("%s %g\n", meno_priezvisko[pozicia[i]], vysledky[i]);
		}
	}
	free(pozicia);
	free(vysledky);
}
//po stlaceni p
void prikaz_p(char**rodne_cislo,char**vysetrenie,char**datum,char**vysledok,FILE*fr,int zoznam) {
	char r[15],v[50],d[10],c[10];
	if (vysetrenie == NULL) {
		printf("Polia nie su vytvorene\n");
		return;
	}
	if (fr == NULL) {
		printf("Neotvoreny subor\n");
		return;
		}
	printf("Zadaj rodne cislo\n");
	scanf("%s",&r);
	printf("Zadaj vysetrenie\n");
	scanf("%s", &v);
	printf("Zadaj datum\n");
	scanf("%s", &d);
	printf("Zadaj vysledok\n");
	scanf("%s", &c);
	//zmena vysledku(v poli som to nestihol zmenit)
	for (int i = 0;i < zoznam;i++) {
		if ((strcmp(rodne_cislo[i],r)==0)&&(strcmp(vysetrenie[i],v)==0)&&(strcmp(datum[i],d)==0)) {
			strcpy(vysledok[i],c);
			break;
		}
		printf("%d\n",i);
	}
}
//hlavny program
int main() {
	char d,d1,**meno_priezvisko=NULL,**rodne_cislo=NULL,**diagnoza=NULL,**vysetrenie=NULL,**vysledok=NULL,**datum=NULL;
	int zoznam=0;
	FILE* fr=NULL;
	do {
		printf("Zadaj prikaz\n");
		d = getchar();
		d1 = getchar();
		switch (d) {
		case 'v':
			fr=prikaz_v();break;
		case 'o':
			prikaz_o(fr,zoznam);
			d1 = getchar();break;
		case 'n':
			prikaz_n(&meno_priezvisko,&rodne_cislo,&diagnoza,&vysetrenie,&vysledok,&datum,&zoznam,fr); break;
		case 's':
			prikaz_s(rodne_cislo,vysetrenie,vysledok,zoznam);break;
		case 'h':
			prikaz_h(diagnoza,rodne_cislo,zoznam);break;
		case 'p':
			prikaz_p(rodne_cislo,vysetrenie,datum,vysledok,fr,zoznam);break;
		case 'z':
			prikaz_z(vysetrenie,vysledok,meno_priezvisko,datum,zoznam);break;
		}
	} while (d!='k');
	if (meno_priezvisko != NULL) {
		free(meno_priezvisko);
		free(rodne_cislo);
		free(diagnoza);
		free(vysetrenie);
		free(vysledok);
		free(datum);
	}
	if (fr != NULL) {
		fclose(fr);
	}
}