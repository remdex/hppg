#include <stdlib.h>
#include <stdio.h>
#include <math.h>
#include <cv.h>
#include <highgui.h>
        
/**
* Reference
* http://www.compuphase.com/cmetric.htm
*/
typedef struct {
   unsigned char r, g, b;
} RGB;

double getDistance(RGB e1, RGB e2)
{
  long rmean = ( (long)e1.r + (long)e2.r ) / 2;
  long r = (long)e1.r - (long)e2.r;
  long g = (long)e1.g - (long)e2.g;
  long b = (long)e1.b - (long)e2.b;
  return sqrt((((512+rmean)*r*r)>>8) + 4*g*g + (((767-rmean)*b*b)>>8));
}

// Our pallete item
typedef struct {
    int pallete_id,red,green,blue,count;
} palleteItem;

int main(int argc, char *argv[])
{
  IplImage* img = 0; 
  int height,width,step,channels;
  uchar *data;
  int i,j,k,treshold;
  RGB RGBImage,RGBPallete;
  palleteItem palletesp[200];
  
  FILE * filehandle;
  char lyne[121];
    
  char *item,*palleteFile;
  int reccount = 0;
  
  uchar *aPixelIn, *aPixelOut;
    
  if(argc<4){
    printf("Usage: color_indexer <image-file-name> <pallete-file-name> <threshold>\n\7");
    exit(0);
  }
  
  // Our treshold
  treshold = atoi(argv[3]);
  
  // Pallete txt file
  palleteFile = argv[2];
   
  
  // load an image  
  img=cvLoadImage(argv[1]);
  if(!img){
    printf("Could not load image file: %s\n",argv[1]);
    exit(0);
  }

  /*
  * Reference
  * http://www.wellho.net/resources/ex.php4?item=c209/lunches.c
  */
  filehandle = fopen(palleteFile,"r");
  /* Read file line by line */    
  while (fgets(lyne,120,filehandle)) {
   
    item = strtok(lyne,";");
    palletesp[reccount].pallete_id = atoi(item);

    item = strtok(NULL,";");
    palletesp[reccount].red = atoi(item);
    
    item = strtok(NULL,";");
    palletesp[reccount].green = atoi(item);
    
    item = strtok(NULL,";");
    palletesp[reccount].blue = atoi(item);
    
    palletesp[reccount].count = 0;
   
    reccount++;    
   }
   
   /* Close file */
   fclose(filehandle);
     
   
  // get the image data
  height    = img->height;
  width     = img->width;
  step      = img->widthStep;
  channels  = img->nChannels;
  data      = (uchar *)img->imageData;
  //printf("Processing a %dx%d image with %d channels\n",height,width,channels); 
  
  aPixelIn = (uchar *)img->imageData;
  aPixelOut = (uchar *)img->imageData;
    
  
  for(i=0;i<height;i++) {
    for(j=0;j<width;j++) {
        
            int R, B, G, currentPalleteKey = 0;
            double minimumdistance = -1,currentDistance = 0;
                       
            B = aPixelIn[ i * img->widthStep + j * 3 + 0 ];
            G = aPixelIn[ i * img->widthStep + j * 3 + 1 ];
            R = aPixelIn[ i * img->widthStep + j * 3 + 2 ];
            
            RGBImage.r = (char)R;
            RGBImage.g = (char)G;
            RGBImage.b = (char)B;
            
            for (k=0; k<reccount; k++) {
                RGBPallete.r = palletesp[k].red;
                RGBPallete.g = palletesp[k].green;
                RGBPallete.b = palletesp[k].blue;
                
                currentDistance = getDistance(RGBImage,RGBPallete);
                
                if (minimumdistance < 0 ||  minimumdistance > currentDistance){
                    minimumdistance = currentDistance;                    
                    currentPalleteKey = k;
                }
            }
            
            palletesp[currentPalleteKey].count++;                                    
    }
  }

  for (k=0; k<reccount; k++) {
         if (palletesp[k].count > treshold)
               printf("%d-%d\n",palletesp[k].pallete_id,palletesp[k].count);
  }
     
  // release the image
  cvReleaseImage(&img );
  return 0;
}
