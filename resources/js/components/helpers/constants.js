const prod = {
    url: "http://localhost:8080"
};

const dev = {
    url: "http://localhost:8000"
};

export default process.env.NODE_ENV === "development" ? dev : prod;
