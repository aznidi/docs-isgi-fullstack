import React from 'react'
import Hero from '../components/landing/Hero'
import FAQ from '../components/landing/FAQ'
import Trends from '../components/landing/Trends'
import Founders from '../components/landing/Founders'
import MustDownloadedDocs from '../components/landing/MustDownloadedDocs'
import OurStatistics from '../components/landing/OurStatistics'
import About from '../components/landing/About'

function HomePage() {
  return (
    <>
        <Hero />
        <Trends />
        <MustDownloadedDocs />
        <OurStatistics />
        <FAQ />
        <About />
    </>
  )
}

export default HomePage
