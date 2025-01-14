import React, { useContext } from "react";
import { Outlet } from "react-router-dom";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import { AuthContext } from "../context/AuthContext";

function AuthLayout() {
  const { isLoggedIn } = useContext(AuthContext);

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

export default AuthLayout;
