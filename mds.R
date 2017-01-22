mydata <- read.csv("C:/xampp/htdocs/Master_Project/file_finger_football_sorted.csv")


dotchart(mydata,labels=row.names(mydata),cex=.7)



mydata <- read.csv("C:/xampp/htdocs/Master_Project/file_4Cats_small.csv")
row.names(mydata) <- mydata[, 1]
firstCol<-mydata[,1]
mydata<- mydata[, -1]

d <- dist(mydata) # euclidean distances between the rows
fit <- cmdscale(d,eig=TRUE, k=2) # k is the number of dim

# plot solution
x <- fit$points[,1]
y <- fit$points[,2]
plot(x, y, xlab="Coordinate 1", ylab="Coordinate 2",
     main="Metric MDS", type="n")
text(x, y, labels = firstCol, cex=.7, col = rainbow(4)[firstCol]) 


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

