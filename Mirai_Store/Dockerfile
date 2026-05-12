# Giai đoạn Build
FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build
WORKDIR /app

# Copy file csproj và restore dependencies
COPY *.csproj ./
RUN dotnet restore

# Copy toàn bộ code và build
COPY . ./
RUN dotnet publish -c Release -o out

# Giai đoạn Run
FROM mcr.microsoft.com/dotnet/aspnet:10.0
WORKDIR /app
COPY --from=build /app/out .

# Mở port 5185 (theo cấu hình trong .env của bạn)
EXPOSE 5185
ENV ASPNETCORE_URLS=http://+:5185

ENTRYPOINT ["dotnet", "Mirai_Store.dll"]
