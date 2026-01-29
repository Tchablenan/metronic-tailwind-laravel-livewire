// src/pages/Home.tsx
import Header from '@components/common/Header'
import Footer from '@components/common/Footer'
import HeroSection from '@components/home/HeroSection'
import AboutSection from '@/components/home/AboutSection'
import ServicesSection from '@/components/home/ServicesSection'
import BlogSection from '@/components/home/BlogSection'
import FAQSection from '@/components/home/FAQSection'
import PartnersSection from '@/components/home/PartnersSection'
import TestimonialsSection from '@/components/home/TestimonialsSection'
import ContactSection from '@/components/home/ContactSection'
import TeamSection from '@/components/home/TeamSection'
import Loader from '../components/common/Loader'

function Home() {
  return (
    <>
      <Loader />
      {/* Header */}
      <Header />

      {/* Hero Section avec formulaire */}
      <HeroSection />

      <AboutSection />

      <ServicesSection />

      <BlogSection />

      <FAQSection />
<TeamSection />
      <PartnersSection />

      <TestimonialsSection />

      <ContactSection />

      {/* Services rapides (4 cards) */}
    
      {/* Grille de services détaillés */}


      {/* TODO: Ajouter les autres sections progressivement */}
      {/* - HospitalBranch */}
      {/* - SuccessStories */}
      {/* - TeamSection */}
      {/* - Testimonials */}
      {/* - WhyChooseUs */}
      {/* - BlogSection */}
      {/* - CTASection */}
      {/* - BrandSection */}

      {/* Footer */}
      <Footer />
    </>
  )
}

export default Home