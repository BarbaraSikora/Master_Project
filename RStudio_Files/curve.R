uk-news	201
world	130
sport	134
football	143
opinion	7
culture	5
fashion	101
business	1
lifeandstyle	3
environment	3
technology	2
travel	1

names <- c("uk-news","world news","sport","football"
           ,"opinion","culture","fashion","business","life and style",
           "environment","technology","travel")

counts <- c(201,130,134,143,7,5,101,1,3,3,2,1)

par(las=2) # make label text perpendicular to axis
par(mar=c(5,11,7,2))

barplot(counts, cex.names=2,horiz=TRUE,
        names.arg=names,cex=1.5,space=0.5)

#############################################

names <- c(
"uk news",
"business",
"opinion",
"sport",
"society",
"politics",
"environment",
"world news",
"technology",
"life and style",
"culture",
"television & radio",
"books",
"film",
"art and design",
"fashion",
"travel",
"football",
"us news",
"science")


counts <- c(
  111,
  125,
  120,
  142,
  113,
  117,
  125,
  103,
  104,
  114,
  102,
  114,
  103,
  115,
  83,
  130,
  133,
  148,
  102,
  91)

par(las=2) # make label text perpendicular to axis
par(mar=c(3,14,2,1))

barplot(counts, cex.names=1.8,cex=1.5,horiz=TRUE,
        names.arg=names,xlim=c(0,10+max(counts)),space=1)

------------------------------


par(mar = c(8, 4, 0, 2) + 0.2) #add room for the rotated labels

end_point = 0.5 + 20 + 20 -1 #this is the line which does the trick (together with barplot "space = 1" parameter)

barplot(counts, col="grey", 
        main="",
        ylab="mtcars - qsec", ylim=c(0,10+max(counts)),
        xlab = "",
        space=1)
#rotate 60 degrees, srt=60
text(seq(1.5,end_point,by=2), par("usr")[3]-3.5, 
     srt = 60, adj= 1, xpd = TRUE,
     labels = paste(names), cex=1.2)

------------------------------
library(plotrix)

par(mar = c(10, 3, 1, 2) + 0.2)
barp(counts,col="grey",names.arg=names,staxx=TRUE,srt=60,cex.axis=1.7,
     xlab="",ylab="",ylim=c(0,10+max(counts)),
     do.first=expression(abline(h=seq(10,150,10))))

(c(0.6,0.4,0.6,0.2,0.6,0.6,0.4,0.8,0.8,0.6,0.8,0.6,0.8,0.6,0.95,0.4,0.4,0.2,0.8,0.95))

------------------------------

  x <- c(5,15,25,35,45,55,65,75,85,
         95,
         105,
         115,
         125,
         135,
         145,
         155,
         165,
         175,
         185,
         195,
         205,
         215,
         225,
         235,
         245
  )

y1 <-c(
  0.738,
  0.695,
  0.734,
  0.725,
  0.734,
  0.725,
  0.716,
  0.712,
  0.712,
  0.708,
  0.716,
  0.703,
  0.695,
  0.682,
  0.682,
  0.661,
  0.631,
  0.613,
  0.618,
  0.609,
  0.592,
  0.579,
  0.566,
  0.558,
  0.558
)

y2<-c(
  0.729,
  0.708,
  0.751,
  0.729,
  0.746,
  0.738,
  0.721,
  0.716,
  0.721,
  0.734,
  0.734,
  0.734,
  0.725,
  0.734,
  0.734,
  0.725,
  0.716,
  0.721,
  0.725,
  0.721,
  0.716,
  0.721,
  0.712,
  0.708,
  0.708
)

y3 <-c(
  0.738,
  0.695,
  0.734,
  0.725,
  0.734,
  0.725,
  0.716,
  0.712,
  0.712,
  0.708,
  0.716,
  0.703,
  0.695,
  0.682,
  0.682,
  0.661,
  0.631,
  0.613,
  0.618,
  0.609,
  0.592,
  0.579,
  0.566,
  0.558,
  0.558
)

xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)
yrange <- range(yrange,y3)

plot(xrange, yrange, type="n", xlab ="k value",ylab="Accuracy",cex.lab=1.5,xaxt="n")
axis(1, at = seq(5, 245, by = 10), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)
lines(x, y3, type="s",col="green",lwd=3)

legend("bottomleft", c("Majority Decision (Cosine Sim.)","Highest Similarity per Class", "Majority Decision (Euclidean Dist.)"), col = c("red","blue","green"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 

-----------------------------------------------
  

  x <- c(5,15,25,35,45,55,65,75,85,
         95,
         105,
         115,
         125,
         135,
         145,
         155,
         165,
         175,
         185,
         195,
         205,
         215,
         225,
         235,
         245
  )

y1<-c(0.729,
      0.708,
      0.751,
      0.729,
      0.746,
      0.738,
      0.721,
      0.716,
      0.721,
      0.734,
      0.734,
      0.734,
      0.725,
      0.734,
      0.734,
      0.725,
      0.716,
      0.721,
      0.725,
      0.721,
      0.716,
      0.721,
      0.712,
      0.708,
      0.708
      
)

y2<-c(0.702,
      0.716,
      0.723,
      0.73,
      0.737,
      0.751,
      0.751,
      0.744,
      0.751,
      0.758,
      0.758,
      0.744,
      0.758,
      0.758,
      0.758,
      0.758,
      0.751,
      0.744,
      0.744,
      0.744,
      0.744,
      0.744,
      0.737,
      0.73,
      0.73
)

y3<-c(0.716,
      0.754,
      0.764,
      0.754,
      0.745,
      0.754,
      0.745,
      0.754,
      0.764,
      0.764,
      0.764,
      0.764,
      0.764,
      0.783,
      0.783,
      0.773,
      0.773,
      0.764,
      0.754,
      0.754,
      0.754,
      0.754,
      0.754,
      0.754,
      0.754
)

y4<-c(0.742,
      0.771,
      0.785,
      0.771,
      0.785,
      0.8,
      0.785,
      0.771,
      0.771,
      0.771,
      0.771,
      0.771,
      0.771,
      0.771,
      0.8,
      0.8,
      0.8,
      0.785,
      0.8,
      0.785,
      0.785,
      0.785,
      0.785,
      0.785,
      0.785
)

xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)
yrange <- range(yrange,y3)
yrange <- range(yrange,y4)


plot(xrange, yrange, type="n", xlab ="k value",ylab="Accuracy",cex.lab=1.5,xaxt="n")
axis(1, at = seq(5, 245, by = 10), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)
lines(x, y3, type="b",col="green",lwd=3)
lines(x, y4, type="b",col="orange",lwd=3)

legend("bottomright", c("Ratio 67:33","Ratio 80:20", "Ratio 85:15","Ratio 90:10"), col = c("red","blue","green","orange"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 

------------------------------------------------------------------------
  
x<-c(5,15,25,35,45,55,65,75,85,
     95,
     105,
     115,
     125,
     135,
     145,
     155,
     165,
     175)

y1<-c(
  0.684,
  0.701,
  0.676,
  0.68,
  0.672,
  0.676,
  0.676,
  0.68,
  0.672,
  0.68,
  0.688,
  0.672,
  0.664,
  0.643,
  0.639,
  0.634,
  0.618,
  0.61
)

y2<-c(
  0.709,
  0.722,
  0.705,
  0.709,
  0.697,
  0.697,
  0.701,
  0.709,
  0.701,
  0.709,
  0.701,
  0.701,
  0.705,
  0.697,
  0.697,
  0.697,
  0.697,
  0.693
)

y3<-c(
  0.966666667,
  0.966666667,
  0.955555556,
  0.944444444,
  0.944444444,
  0.944444444,
  0.944444444,
  0.944444444,
  0.944444444,
  0.944444444,
  0.944444444,
  0.933333333,
  0.933333333,
  0.933333333,
  0.933333333,
  0.933333333,
  0.944444444,
  0.933333333
)
  
y4<-c(
  0.944444444,
  0.955555556,
  0.944444444,
  0.944444444,
  0.933333333,
  0.911111111,
  0.922222222,
  0.922222222,
  0.933333333,
  0.933333333,
  0.933333333,
  0.911111111,
  0.888888889,
  0.877777778,
  0.844444444,
  0.744444444,
  0.622222222,
  0.5
)
  
  
  
  
xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)
yrange <- range(yrange,y3)
yrange <- range(yrange,y4)


plot(xrange, yrange, type="n", xlab ="k value",ylab="Accuracy",cex.lab=1.5,xaxt="n")
axis(1, at = seq(5, 185, by = 10), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)
lines(x, y3, type="b",col="green",lwd=3)
lines(x, y4, type="b",col="orange",lwd=3)

legend("bottomleft", c("12 Classes (majority)","12 Classes (highest Sim.)", "2 Classes (highest Sim.)","2 Classes (majority)"), col = c("red","blue","green","orange"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 

-------------------------------------------------------------
  x <- c(5,15,25,35,45,55,65,75,85,
         95,
         105,
         115,
         125,
         135,
         145,
         155,
         165,
         175,
         185,
         195,
         205,
         215,
         225,
         235,
         245
  )

y1<-c(
  0.868,
  0.868,
  0.885,
  0.86,
  0.877,
  0.852,
  0.844,
  0.844,
  0.836,
  0.837,
  0.836,
  0.839,
  0.819,
  0.811,
  0.811,
  0.811,
  0.803,
  0.811,
  0.819,
  0.819,
  0.811,
  0.819,
  0.811,
  0.811,
  0.811
)

y2<-c(
  0.878,
  0.869,
  0.852,
  0.852,
  0.843,
  0.834,
  0.826,
  0.834,
  0.834,
  0.826,
  0.826,
  0.817,
  0.808,
  0.774,
  0.756,
  0.756,
  0.747,
  0.747,
  0.747,
  0.747,
  0.747,
  0.747,
  0.747,
  0.747,
  0.747
)

y3<-c(
  0.718,
  0.757,
  0.815,
  0.796,
  0.825,
  0.825,
  0.835,
  0.835,
  0.815,
  0.825,
  0.825,
  0.825,
  0.835,
  0.825,
  0.825,
  0.815,
  0.815,
  0.815,
  0.825,
  0.796,
  0.786,
  0.796,
  0.796,
  0.786,
  0.796
)

y4<-c(
  0.958,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95,
  0.933,
  0.95,
  0.95,
  0.95,
  0.95,
  0.95
)
xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)
yrange <- range(yrange,y3)
yrange <- range(yrange,y4)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n",ylim=c(0.60,0.95), xlab ="k value",ylab="Accuracy",cex.lab=2,xaxt="n")
axis(1, at = seq(5, 245, by = 10), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)
lines(x, y3, type="b",col="green",lwd=3)
lines(x, y4, type="b",col="orange",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business",
                       "Politics, World-News, Lifestyle, Environment, Technology",
                       "TV/Radio, Culture, Art/Design, Film, Books",
                       "US-News, Football, Fashion, Travel, Science"), col = c("red","blue","green","orange"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 
-------------------------------
  x <- c(5,15,25,35,45,55,65,75,85,
         95,
         105,
         115,
         125,
         135,
         145,
         155,
         165,
         175,
         185,
         195,
         205,
         215,
         225,
         235,
         245,
         265,
         285,
         305,
         325,
         345,
         365,
         385,
         405,
         425,
         445,
         465,
         485,
         505,
         525,
         545,
         565,
         585,
         605,
         625,
         645,
         665,
         685,
         705,
         725,
         745,
         765,
         785,
         805,
         825,
         845,
         865,
         885
  )

y1<-c(
  0.67,
  0.696,
  0.691,
  0.691,
  0.7,
  0.691,
  0.691,
  0.687,
  0.682,
  0.658,
  0.67,
  0.675,
  0.67,
  0.667,
  0.667,
  0.658,
  0.662,
  0.65,
  0.658,
  0.654,
  0.641,
  0.649,
  0.645,
  0.649,
  0.645,
  0.645,
  0.637,
  0.641,
  0.632,
  0.637,
  0.645,
  0.641,
  0.632,
  0.632,
  0.632,
  0.641,
  0.645,
  0.649,
  0.649,
  0.649,
  0.645,
  0.645,
  0.645,
  0.649,
  0.645,
  0.654,
  0.658,
  0.658,
  0.658,
  0.658,
  0.658,
  0.658,
  0.658,
  0.662,
  0.667,
  0.667,
  0.667
)

y2<-c(
  0.831,
  0.848,
  0.853,
  0.862,
  0.862,
  0.867,
  0.871,
  0.88,
  0.875,
  0.88,
  0.884,
  0.875,
  0.875,
  0.875,
  0.875,
  0.875,
  0.871,
  0.88,
  0.88,
  0.875,
  0.884,
  0.884,
  0.893,
  0.884,
  0.888,
  0.871,
  0.875,
  0.88,
  0.88,
  0.875,
  0.871,
  0.875,
  0.871,
  0.866,
  0.871,
  0.871,
  0.871,
  0.875,
  0.871,
  0.867,
  0.871,
  0.88,
  0.875,
  0.867,
  0.867,
  0.867,
  0.867,
  0.871,
  0.871,
  0.871,
  0.867,
  0.857,
  0.857,
  0.862,
  0.862,
  0.862,
  0.862
)



xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n", ylim=c(0.60,0.95),xlab ="k value",ylab="Accuracy",cex.lab=2,xaxt="n")
axis(1, at = seq(5, 885, by = 20), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
                           "TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science"),
                           col = c("red","blue"),cex=1.4,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 

--------------------------------------------

names<-c("1 Sport, UK-News, Opinion, Society, Business",
         "2 Politics, World-News, Lifestyle, Environment, Technology",
         "3 TV/Radio, Culture, Art/Design, Film, Books",
         "4 US-News, Football, Fashion, Travel, Science")

counts <- matrix(c(0.868852459,
                     0.869565217,
                     0.757281553,
                     0.950413223,
                     0.852459016,
                     0.848214286,
                     0.776699029,
                     0.983333333),ncol=4,byrow=TRUE)

colnames(counts) <- c("1","2","3","4")
rownames(counts) <- c("Before Noise Reduction","After Noise Reduction")
counts <- as.table(counts)
counts

par(mar=c(4, 4,9, 4))
layout(rbind(1,2), heights=c(7,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("grey","dimgrey"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.5) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("right", bty = "n",rownames(counts),col=c("grey","dimgrey"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 


legend("left", bty = "n",names,cex=1.2, y.intersp = 0.4,x.intersp=0.4) 

---------------------------------------------------
  

  names<-c("1 Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
           "2 TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science",
           "3 All Twenty Categories")
  


counts <- matrix(c(0.696202532,
                   0.848888889,
                   0.686825054,
                   0.723404255,
                   0.830357143,
                   0.699346405),ncol=3,byrow=TRUE)

colnames(counts) <- c("1","2","3")
rownames(counts) <- c("Before Noise Red.","After Noise Red.")
counts <- as.table(counts)
counts

par(mar=c(4, 4,9, 1))
layout(rbind(1,2), heights=c(7,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("grey","dimgrey"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.2) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("right", bty = "n",rownames(counts),col=c("grey","dimgrey"),cex=1.1,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 
legend("left", bty = "n",names,cex=1.1, y.intersp = 0.4,x.intersp=0.4) 

-------------------------------------------------------
  x<-c(
    5,
    25,
    45,
    65,
    85,
    105,
    125,
    145,
    165,
    185,
    205,
    225,
    245,
    265,
    285,
    305,
    325,
    345,
    365,
    385,
    405
  )

y1<-c(
  0.705,
  0.726,
  0.713,
  0.705,
  0.705,
  0.692,
  0.683,
  0.671,
  0.679,
  0.683,
  0.692,
  0.688,
  0.688,
  0.688,
  0.671,
  0.671,
  0.679,
  0.679,
  0.679,
  0.679,
  0.679
)

y2<-c(
  0.817,
  0.843,
  0.843,
  0.843,
  0.834,
  0.839,
  0.839,
  0.825,
  0.834,
  0.834,
  0.834,
  0.834,
  0.834,
  0.839,
  0.839,
  0.839,
  0.839,
  0.839,
  0.839,
  0.83,
  0.825
)
xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n", ylim=c(0.65,0.95),xlab ="k value",ylab="Accuracy",cex.lab=2,xaxt="n")
axis(1, at = seq(5, 885, by = 20), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
                           "TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science"),
       col = c("red","blue"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 
--------------------------------------------------# SEMFP
  
  x<-c(
    90,
    112,
    135,
    157,
    180,
    202,	
    225,
    247,
    270,
    292,
    315,
    337,
    360,
    382,
    405,
    427,
    450,
    472,
    495,
    517,
    540,
    562,
    585,
    607,
    630,
    652,
    675,
    697,
    720,
    742,
    765,
    787,
    810,
    832
  )
  

y1<-c(
  63.67,
  61.11,
  61.96,
  63.24,
  63.67,
  61.11,
  61.53	,
  61.53	,
  58.97,
  60.25,
  62.39	,
  60.68	,
  58.54	,
  58.54	,
  58.97	,
  57.26	,
  58.11	,
  55.98	,
  56.83	,
  58.54	,
  59.4	,
  57.69	,
  57.69	,
  55.98	,
  58.12	,
  59.82	,
  57.26	,
  54.27,
  51.71	,
  52.13	,
  48.29	,
  51.28	,
  42.73	,
  41.88
)

y2<-c(
  65.17,
  64.73	,
  67.85	,
  74.53	,
  75	,
  69.64,	
  70.53	,
  73.21	,
  72.76	,
  72.32	,
  71.87	,
  73.66	,
  70.53	,
  68.75	,
  65.62	,
  69.19	,
  65.17	,
  66.07	,
  65.62	,
  68.3	,
  67.85	,
  66.51	,
  69.64	,
  66.07	,
  66.51	,
  73.21	,
  65.62	,
  66.51	,
  64.73	,
  65.17	,
  56.7	,
  61.16	,
  51.78	,
  49.1
)

xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n", ylim=c(40,90), xlab ="threshold",ylab="Accuracy",cex.lab=1.8,xaxt="n")
axis(1, at = seq(90, 832, by = 20), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
                           "TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science"),
       col = c("red","blue"),cex=1.4,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 

----------------------------------------------------------------------
  
  
  x<-c(
    10,
    15,
    20,
    25,
    30,
    35,
    40,
    45,
    50,
    55,
    60,
    65,
    70,
    75,
    80,
    85,
    90,
    95,
    100,
    105,
    110,
    115,
    120,
    125,
    130,
    135,
    140,
    145,
    150,
    155,
    160,
    165,
    170,
    175,
    180,
    185,
    190,
    195,
    200,
    205,
    210,
    215,
    220,
    225,
    230,
    235,
    240,
    245,
    250,
    255,
    260,
    265,
    270,
    275,
    280,
    285,
    290,
    295,
    300,
    305,
    310,
    315,
    320,
    325,
    330,
    335,
    340,
    345,
    350,
    355,
    360,
    365,
    370,
    375,
    380,
    385,
    390,
    395,
    400
  )


y1<-c(
  69.67,
  70.49,
  72.13,
  72.13	,
  72.95	,
  71.31	,
  71.31	,
  73.77	,
  76.23	,
  77.86	,
  77.04	,
  74.6	,
  77.86	,
  78.68	,
  77.04	,
  77.86,
  77.86,
  77.86,
  77.86,
  77.05,
  78.68,
  79.5,
  77.86,
  76.23,
  77.05,
  77.86,
  78.68,
  77.05,
  77.05,
  76.23,
  77.86,
  75.41	,
  76.23	,
  80.32	,
  78.68	,
  80.32	,
  77.86	,
  77.05	,
  77.86	,
  78.68	,
  77.05	,
  74.6	,
  75.41	,
  75.41	,
  73.77	,
  74.6	,
  76.23	,
  76.23	,
  75.41	,
  75.41	,
  75.41	,
  77.05	,
  76.23	,
  75.41	,
  76.23	,
  77.86	,
  77.05	,
  77.05	,
  74.6	,
  75.41	,
  77.05	,
  75.41	,
  75.41	,
  73.77	,
  72.95	,
  72.13	,
  67.21	,
  65.57	,
  63.11	,
  70.5	,
  66.39	,
  66.4	,
  69.67	,
  68.03	,
  63.11	,
  67.21	,
  65.57	,
  66.4	,
  61.47
)

y2<-c(
  67.85,
  63.4	,
  78.57	,
  77.67	,
  74.1	,
  68.75	,
  70.53	,
  69.64	,
  67.85	,
  75	,
  71.42,	
  71.42	,
  72.32	,
  72.32	,
  75	,
  75.89,	
  71.42	,
  75	,
  75.89,	
  75.89	,
  75.89	,
  73.21	,
  71.42	,
  75	,
  75	,
  75.89,	
  75.89	,
  75	,
  75	,
  76.78,	
  74.1	,
  73.21	,
  78.57	,
  77.67	,
  80.35	,
  79.46	,
  77.67	,
  77.67	,
  75.89	,
  74.1	,
  73.21	,
  75	,
  72.32,	
  73.21	,
  71.42	,
  69.64	,
  67.85	,
  65.17	,
  69.64	,
  69.64	,
  75.89	,
  72.32	,
  70.53	,
  69.64	,
  67.85	,
  72.32	,
  72.32	,
  78.57	,
  75.89	,
  75	,
  71.42	,
  73.21,	
  69.64	,
  74.1	,
  73.21	,
  72.32	,
  70.53	,
  70.53	,
  64.28	,
  66.07	,
  66.94	,
  62.5	,
  59.82	,
  64.28	,
  62.5	,
  64.28	,
  61.6	,
  59.82	,
  60.71
)

y3<-c(
  35.92	,
  40.77	,
  50.48	,
  52.42	,
  61.16	,
  58.25	,
  61.16	,
  61.16	,
  66.99	,
  62.13	,
  61.16	,
  60.19	,
  65.94	,
  62.13	,
  66.01	,
  66.01	,
  67.96	,
  61.16	,
  66.99	,
  63.1	,
  65.04	,
  64.07	,
  64.07	,
  66.99	,
  62.13	,
  61.16	,
  62.13	,
  64.97	,
  66.02	,
  64.07	,
  64.07	,
  57.28	,
  61.16	,
  58.25	,
  60.19	,
  63.1	,
  66.99	,
  65.04	,
  64.07	,
  59.22	,
  59.22	,
  61.16	,
  50.48	,
  52.42	,
  48.54	,
  51.45	,
  46.6	,
  55.34	,
  53.4	,
  46.6	,
  56.31	,
  51.45	,
  52.42	,
  53.4	,
  54.36	,
  55.34	,
  54.36	,
  54.36	,
  56.31	,
  51.45	,
  48.54	,
  52.42	,
  51.45	,
  53.4	,
  56.31	,
  51.45	,
  52.42	,
  49.51	,
  45.63	,
  45.63	,
  46.6	,
  44.66	,
  51.45	,
  46.6	,
  33.98	,
  34.95	,
  33.01	,
  33.98	,
  33.01
)

y4<-c(
  85	,
  86.67,	
  93.33,
  90.83,
  90.83,
  90	,
  90	,
  92.5,
  95	,
  90.83,
  90	,
  88.33,
  89.17,
  90,
  93.33,
  90	,
  91.67,	
  90.83	,
  90.83	,
  86.67	,
  88.33	,
  88.33	,
  88.33	,
  85.83	,
  86.67	,
  85.83	,
  90.83	,
  92.5	,
  89.17	,
  92.5	,
  94.17	,
  92.5	,
  89.17	,
  90	,
  94.17,
  93.33	,
  92.5	,
  91.67	,
  89.17	,
  90.83	,
  90	,
  90.83,	
  91.67	,
  90.83	,
  88.33	,
  89.17	,
  88.33	,
  87.5	,
  86.67	,
  88.33	,
  89.17	,
  90.83	,
  88.33	,
  90.83	,
  90.84	,
  90.83	,
  94.17	,
  94.17	,
  94.17	,
  93.33	,
  92.5	,
  93.33	,
  94.17	,
  94.17	,
  92.5	,
  94.17	,
  93.33	,
  95	,
  94.17,
  92.5	,
  93.33	,
  90	,
  91.67,	
  91.67	,
  89.17	,
  85	,
  85	,
  85	,
  86.67
)

xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)
yrange <- range(yrange,y3)
yrange <- range(yrange,y4)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n", xlab ="threshold",ylab="Accuracy",cex.lab=1.8,xaxt="n")
axis(1, at = seq(10, 400, by = 10), las=1)
lines(x, y1, type="b",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)
lines(x, y3, type="b",col="green",lwd=3)
lines(x, y4, type="b",col="orange",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business",
                           "Politics, World-News, Lifestyle, Environment, Technology",
                           "TV/Radio, Culture, Art/Design, Film, Books",
                           "US-News, Football, Fashion, Travel, Science"), col = c("red","blue","green","orange"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 

----------------------------------------------------------------------
  
  
  names<-c("1 Sport, UK-News, Opinion, Society, Business",
           "2 Politics, World-News, Lifestyle, Environment, Technology",
           "3 TV/Radio, Culture, Art/Design, Film, Books",
           "4 US-News, Football, Fashion, Travel, Science")

counts <- matrix(c(0.751072961,
                   0.977777778,
                   0.832335329,
                   0.697095436,
                   0.765957447,
                   0.981481481,
                   0.851485149,
                   0.698630137,
                   0.783018868,
                   1.0,
                   0.855263158,
                   0.678899083,
                   0.8,
                   1.0,
                   0.84,
                   0.671232877),ncol=4,byrow=TRUE)

colnames(counts) <- c("5 classes","2 classes","4 classes","12 classes")
rownames(counts) <- c("Ratio 67:20","Ratio 80:20","Ratio 85:15","Ratio 90:10")
counts <- as.table(counts)
counts

par(mar=c(4, 4,9, 4))
layout(rbind(1,2), heights=c(7,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("firebrick","cyan4","chartreuse3","gold"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.5) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",rownames(counts),col=c("firebrick","cyan4","chartreuse3","gold"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 

------------------------------------------------------------------
 
  names<-c("1 Football, World News, Fashion, Sport (3 classes + large class)",
           "2 Football, World News, Fashion, Opinion (3 classes + small class)",
           "3 Football, World News, Fashion, UK-News (3 classes + large class)",
           "4 Football, World News, Fashion, Culture (3 classes + small class)",
           "5 Football, World News, Fashion (3 classes)"
  ) 

counts <- matrix(c(0.832335329,
                   0.952,
                   0.841269841,
                   0.944,
                   0.967479675),ncol=5,byrow=TRUE)

colnames(counts) <- c("1","2","3","4","5")
rownames(counts) <- c("Accuracy")
counts <- as.table(counts)
counts


par(mar=c(7, 4,4, 4))
layout(rbind(1,2), heights=c(5,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("darkgrey"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.5) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",names,cex=1.2, y.intersp = 0.4,x.intersp=0.4) 
-----------------------------------------------------------------------------
  
  
  names<-c("1 Sport, UK-News, Opinion, Society, Business",
           "2 Politics, World-News, Lifestyle, Environment, Technology",
           "3 TV/Radio, Culture, Art/Design, Film, Books",
           "4 US-News, Football, Fashion, Travel, Science")

counts <- matrix(c(0.844262295,
                   0.87826087,
                   0.796116505,
                   0.950413223,
                   0.844262295,
                   0.901785714,
                   0.776699029,
                   0.983333333
),ncol=4,byrow=TRUE)

colnames(counts) <- c("1","2","3","4")
rownames(counts) <- c("Before Noise Reduction","After Noise Reduction")
counts <- as.table(counts)
counts

par(mar=c(4, 4,9, 4))
layout(rbind(1,2), heights=c(7,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("grey","dimgrey"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.5) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("right", bty = "n",rownames(counts),col=c("grey","dimgrey"),cex=1.2,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 


legend("left", bty = "n",names,cex=1.2, y.intersp = 0.4,x.intersp=0.4) 

---------------------------------------------------------------------------
  
  
  names<-c("1 Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
           "2 TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science",
           "3 All Twenty Categories")



counts <- matrix(c(0.725738397,
                   0.871111111,
                   0.755939525,
                   0.752136752,
                   0.866071429,
                   0.747276688
),ncol=3,byrow=TRUE)

colnames(counts) <- c("1","2","3")
rownames(counts) <- c("Before Noise Red.","After Noise Red.")
counts <- as.table(counts)
counts

par(mar=c(4, 4,9,1))
layout(rbind(1,2), heights=c(7,1))

barplot(counts,horiz = TRUE,
        xlab="Accuracy", 
        col=c("grey","dimgrey"), beside=TRUE,xaxt="n",xlim=c(0,1.2),cex.lab=1.2) 
axis(1, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("right", bty = "n",rownames(counts),col=c("grey","dimgrey"),cex=1.1,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 
legend("left", bty = "n",names,cex=1.1, y.intersp = 0.4,x.intersp=0.4) 
-------------------------------------------------------------------------------------------
 
  
  names<-c("[1, 2, 3] Two Classes",
           "[4, 5, 6, 7] Five Classes",
           "[8, 9] Ten Classes",
           "[10] Twenty Classes")

  
  
  counts <- matrix(c(
    0.958333333,
1,
0.96,
0.844262295,
0.901785714,
0.776699029,
0.983333333,
0.752136752,
0.866071429,
0.747276688,
0.854166667,
1,
0.96,
0.827868852,
0.875,
0.747572816,
0.925,
0.722222222,
0.794642857,
0.699346405),ncol=10,byrow=TRUE)

colnames(counts) <- c("1","2","3","4","5","6","7","8","9","10")
rownames(counts) <- c("First Approach with term frequency","Second Approach w/o term frequency")
counts <- as.table(counts)
counts

par(mar=c(4, 4,6,1))
layout(rbind(1,2), heights=c(6,1))

barplot(counts,horiz = FALSE,
        ylab="Accuracy", 
        col=c("grey","dimgrey"), beside=TRUE,yaxt="n",ylim=c(0,1.2),cex.lab=1.2) 
axis(2, at = seq(0.0, 1.0, by = 0.1), las=1)


par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("right", bty = "n",rownames(counts),col=c("grey","dimgrey"),cex=1.4,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 
legend("left", bty = "n",names,cex=1.4, y.intersp = 0.4,x.intersp=0.4) 
----------------------------------------#SEMANIC FP#--------------------------------------------------
  
x <- c(5,
       8,
       10,
       13,
       15,
       18,
       20,
       23,
       25,
       28,
       30,
       33,
       35,
       38,
       40,
       43,
       45,
       48,
       50,
       53,
       55,
       58,
       60,
       63,
       65,
       68,
       70,
       73,
       75,
       78,
       80
)

y1<-c(
  0.386266094,
  0.515021459,
  0.579399142,
  0.56223176,
  0.639484979,
  0.652360515,
  0.626609442,
  0.660944206,
  0.665236052,
  0.690987124,
  0.665236052,
  0.678111588,
  0.673819742,
  0.712446352,
  0.682403433,
  0.639484979,
  0.656652361,
  0.673819742,
  0.652360515,
  0.64806867,
  0.673819742,
  0.643776824,
  0.635193133,
  0.643776824,
  0.656652361,
  0.630901288,
  0.618025751,
  0.618025751,
  0.630901288,
  0.635193133,
  0.630901288
)

y2<-c(

)



xrange <- range(x)
yrange <- range(y1)
yrange <- range(yrange,y2)

par(mar=c(4, 5,1, 4))
layout(rbind(1,2), heights=c(7,1))

plot(xrange, yrange, type="n", ylim=c(0,0.80),xlab ="threshold",ylab="Accuracy",cex.lab=2,xaxt="n")
axis(1, at = seq(5, 80, by = 5), las=1)
lines(x, y1, type="o",col="red",lwd=3)
lines(x, y2, type="b",col="blue",lwd=3)

par(mar=c(0, 0, 0, 0))
# c(bottom, left, top, right)
plot.new()

legend("left", bty = "n",c("Sport, UK-News, Opinion, Society, Business, Politics, World-News, Lifestyle, Environment, Technology",
                           "TV/Radio, Culture, Art/Design, Film, Books, US-News, Football, Fashion, Travel, Science"),
       col = c("red","blue"),cex=1.4,lty=1,lwd=5, y.intersp = 0.4,x.intersp=0.4) 

