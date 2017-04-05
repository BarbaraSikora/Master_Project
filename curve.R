thres <- c(180,338,225)
acc <- c(80,70,73)

plot(thres, type="b", col="blue", ylim=c(80,70,73))

# Graph trucks with red dashed line and square points
lines(acc, type="b", pch=22, lty=2, col="red")