mydata <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_football02.csv")
mydata2 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_world news02.csv")
fashion <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_fashion.csv")
tech <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_technology.csv")
testfp02 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp02.csv")
testfp <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp.csv")
testfp03 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp03.csv")
testfp04 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp04.csv")
fashiontech01 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp-fashionTech01.csv")
fashiontech02 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp-fashionTech02.csv")
fashiontech03 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp-fashionTech03.csv")
fashiontech04 <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_testfp-fashionTech04.csv")

library(Matrix)

# Example from ?Matrix:::sparseMatrix
i <- c(1:203); 
j <- c(1:193);
x <- as.numeric(as.vector(mydata[1,]))
x2 <- as.numeric(as.vector(mydata2[1,]))
x3 <- as.numeric(as.vector(testfp[1,]))
x4 <- as.numeric(as.vector(testfp02[1,]))
x5 <- as.numeric(as.vector(testfp03[1,]))
x6 <- as.numeric(as.vector(testfp04[1,]))
xf <- as.numeric(as.vector(fashion[1,]))
xt <- as.numeric(as.vector(tech[1,]))
xft1 <- as.numeric(as.vector(fashiontech01[1,]))
xft2 <- as.numeric(as.vector(fashiontech02[1,]))
xft3 <- as.numeric(as.vector(fashiontech03[1,]))
xft4 <- as.numeric(as.vector(fashiontech04[1,]))
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
plot(j,xf)
plot(j,xt)
plot(j,xft1)
plot(j,xft2)
plot(j,xft3)
plot(j,xft4)
stripchart(x2)

#### MDS ab hier


mydata <- read.csv("C:/xampp/htdocs/Master_Project/file_politicsUk.csv")
row.names(mydata) <- mydata[, 1]
firstCol<-mydata[,1]
mydata<- mydata[, -1]

d <- dist(mydata) # euclidean distances between the rows
fit <- cmdscale(d,eig=TRUE, k=2) # k is the number of dim

# plot solution
x <- fit$points[,1]
y <- fit$points[,2]
plot(x, y, xlab="x", ylab="y",
     main="Politics vs Uk News", type="n")
text(x, y, labels = firstCol, cex=1.0, col = c("brown4","darkolivegreen4")[firstCol]) 

legend("topright", c("Politics","Uk News"), col = c("brown4","darkolivegreen4"),cex=1,lty=1,lwd=5, y.intersp = 0.3,x.intersp=0.3) 


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

