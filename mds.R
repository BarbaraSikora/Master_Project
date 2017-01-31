football <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/football-worldnews/file_finger_football02.csv")
world <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/football-worldnews/file_finger_world news02.csv")
fashion <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_fashion.csv")
tech <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_technology.csv")
film <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/film-politics/file_finger_catfp_film.csv")
politics <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/film-politics/file_finger_catfp_politics.csv")

worldfootball01 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/football-worldnews/file_finger_testfp-footballWorld01.csv")
worldfootball02 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/football-worldnews/file_finger_testfp-footballWorld02.csv")

fashiontech01 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_testfp-fashionTech01.csv")
fashiontech02 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_testfp-fashionTech02.csv")
fashiontech03 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_testfp-fashionTech03.csv")
fashiontech04 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/fashion-technology/file_finger_testfp-fashionTech04.csv")

filmpol01 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/film-politics/file_finger_testfp-filmPolitics01.csv")
filmpol02 <- read.csv("C:/xampp/htdocs/Master_Project/csv/fingerprints/film-politics/file_finger_testfp-filmPolitics02.csv")


library(Matrix)

# Example from ?Matrix:::sparseMatrix
i <- c(1:203); 
j <- c(1:193);
k <- c(1:186);

xfb <- as.numeric(as.vector(football[1,]))
xw <- as.numeric(as.vector(world[1,]))
xfbw1 <- as.numeric(as.vector(worldfootball01[1,]))
xfbw2 <- as.numeric(as.vector(worldfootball02[1,]))
x5 <- as.numeric(as.vector(testfp03[1,]))
x6 <- as.numeric(as.vector(testfp04[1,]))
xf <- as.numeric(as.vector(fashion[1,]))
xt <- as.numeric(as.vector(tech[1,]))
xft1 <- as.numeric(as.vector(fashiontech01[1,]))
xft2 <- as.numeric(as.vector(fashiontech02[1,]))
xft3 <- as.numeric(as.vector(fashiontech03[1,]))
xft4 <- as.numeric(as.vector(fashiontech04[1,]))
xfi <- as.numeric(as.vector(film[1,]))
xp <- as.numeric(as.vector(politics[1,]))
xfip1 <- as.numeric(as.vector(filmpol01[1,]))
xfip2 <- as.numeric(as.vector(filmpol02[1,]))
A <- sparseMatrix(i, j, x = x)

print(A)

image(A)


library(hexbin)
x <- rnorm(200)
y <- rnorm(200)
bin<-hexbin(i, x2, xbins=50)
plot(bin, main="Hexagonal Binning") 

plot(i,x)
plot(i,x2)
plot(i,x3)
plot(i,x4)
plot(i,x5)
plot(i,x6)

plot(j,xf,pch=16)
plot(j,xt,pch=16)
plot(j,xft1)# fashion fashion
plot(j,xft2)#tech
plot(j,xft3)#tech fashion
plot(j,xft4)# fashion tech

plot(k,xfi)#film
plot(k,xp)# politics

#übereinander legen von plots
plot(j,xf,pch=16,cex=3.5,col="green",xlab="Contexts", ylab="Highest stacks of word occurrence",
     main="Fashion Fingerprint vs Technology Testfile")
points(xft4,pch=10,cex=3.5,col="black")
legend("center", c("Fashion","Testfile"), col = c("green","black"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 

#übereinander legen von plots
plot(i,xfb,pch=16,cex=3.5,col="red",xlab="Contexts", ylab="Highest stacks of word occurrence",
     main="Football Fingerprint vs Football Testfile")
points(xfbw2,pch=10,cex=3.5,col="black")
legend("center", c("World News","Tesfile"), col = c("red","black"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 

#übereinander legen von plots
plot(k,xfi,pch=16,cex=4,col="green",xlab="Contexts", ylab="Highest stacks of word occurrence",
     main="Film Fingerprint vs Politics Testfile")
points(xfip2,pch=10,cex=4,col="black")
legend("center", c("Film","Testfile"), col = c("green","black"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 


stripchart(x2)

############################################ MDS ab hier

mydata <- read.csv("C:/xampp/htdocs/Master_Project/file_classSimEuclid.csv")
row.names(mydata) <- mydata[, 1]
firstCol<-mydata[,1]
mydata<- mydata[, -1]

d <- dist(mydata) # euclidean distances between the rows
fit <- cmdscale(d,eig=TRUE, k=2) # k is the number of dim

# plot solution
x <- fit$points[,1]
y <- fit$points[,2]
plot(x, y, xlab="x", ylab="y",
     main="Comparison of all Class-Wordlists", type="n")
text(x, y, labels = firstCol, cex=1.8, col = rainbow(20)[firstCol]) 

legend("topright", c("Fashion","US News","Football","Science","Travel"), col = c("brown4","deepskyblue","darkolivegreen4"  ,"darkblue", "darkorange"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 


row.names(mydata) <- mydata[, 1]
mydata<- mydata[, -1]

fit <- cmdscale(mydata, eig = TRUE, k = 2)
x <- fit$points[, 1]
y <- fit$points[, 2]

plot(x, y, xlab="Coordinate 1", ylab="Coordinate 2",
     main="Metric MDS", type="n")
text(x, y, labels = row.names(mydata), cex=0.7) 


mydata <- read.csv("C:/xampp/htdocs/Master_Project/fileOneData60.csv")

row.names(mydata) <- mydata[, 1]
mydata<- mydata[, -1]


library(MASS)
d <- dist(mydata) # euclidean distances between the rows
fit <- isoMDS(mydata, k=2) # k is the number of dim
fit # view results

# plot solution
x <- fit$points[,1]
y <- fit$points[,2]
plot(x, y, xlab="Coordinate 1", ylab="Coordinate 2",
     main="Nonmetric MDS", type="n")
text(x, y, labels = row.names(mydata), cex=.7) 

