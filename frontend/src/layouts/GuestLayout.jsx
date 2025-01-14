import React, { useContext, useEffect } from "react";
import { Outlet, useNavigate } from "react-router-dom";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import { AuthContext } from "../context/AuthContext";
import { HOME_ROUTE } from "../router";

function GuestLayout() {
  const { isLoggedIn } = useContext(AuthContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (isLoggedIn) {
      navigate(HOME_ROUTE); // Redirige si l'utilisateur est connecté
    }
  }, [isLoggedIn, navigate]);

  return (
    <>
      <Navbar isLoggedIn={isLoggedIn} />
      <main className="pt-16 lg:pt-20 px-4 container mx-auto min-h-screen">
        <Outlet />
      </main>
      <Footer />
    </>
  );
}

export default GuestLayout;
