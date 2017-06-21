library(ggplot2)
library(data.table)

randomFile <- 'random.csv'
db <- fread(randomFile)
y <- matrix(db$V1)
y <- matrix(ifelse(y > 0, 1, 0), ncol = 100)

# hypotesis
mean(y)

# image plot
image(y, col = c("white", "black"))

# convergence
seq <- seq(1000, 100000, 1000)
aux <- NULL
for(i in seq) aux <- c(aux, mean(y[1:i]))
    plot(seq, aux, type="b", pch=20, ylim=c(0.48,0.52))

abline(h=0.5, col="red")
