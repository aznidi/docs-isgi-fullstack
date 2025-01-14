import React, { useContext } from "react";
import { Outlet } from "react-router-dom";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import { AuthContext } from "../context/AuthContext";

function Layout() {
  const { isLoggedIn } = useContext(AuthContext);

  return (
    <>
      <Navbar isLoggedIn={isLoggedIn} />
      <main className="lg:pt-20 container mx-auto min-h-screen ">
        <Outlet />
      </main>
      <Footer />
    </>
  );
}

export default Layout;
