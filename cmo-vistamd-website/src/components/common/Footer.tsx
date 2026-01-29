// src/components/Footer.tsx
import { Phone, Mail, MapPin, Facebook, Linkedin, Twitter, Instagram, Heart } from 'lucide-react'
import logoImage from '@/assets/logo-cmo.png'

const Footer = () => {
  const currentYear = new Date().getFullYear()

  const footerLinks = {
    services: [
      { label: 'Services Médicaux', href: '#' },
      { label: 'Transport Médicalisé', href: '#' },
      { label: 'Évacuations Sanitaires', href: '#' },
      { label: 'Assistance Médicale', href: '#' },
      { label: 'Matériel Biomédical', href: '#' }
    ],
    company: [
      { label: 'À Propos', href: '#' },
      { label: 'Nos Services', href: '#' },
      { label: 'Blog', href: '#' },
      { label: 'Carrières', href: '#' },
      { label: 'Contact', href: '#' }
    ],
    legal: [
      { label: 'Politique de Confidentialité', href: '#' },
      { label: 'Conditions d\'Utilisation', href: '#' },
      { label: 'Mentions Légales', href: '#' },
      { label: 'RGPD', href: '#' }
    ]
  }

  const socialLinks = [
    { icon: Facebook, href: '#', label: 'Facebook' },
    { icon: Linkedin, href: '#', label: 'LinkedIn' },
    { icon: Twitter, href: '#', label: 'Twitter' },
    { icon: Instagram, href: '#', label: 'Instagram' }
  ]

  return (
    <footer style={{
      background: 'linear-gradient(135deg, #0f172a 0%, #1a2332 50%, #0f3a2a 100%)',
      color: '#e2e8f0',
      position: 'relative',
      overflow: 'hidden'
    }}>
      {/* Éléments de décoration */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: '-10%',
        width: '300px',
        height: '300px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: 0,
        right: '-10%',
        width: '300px',
        height: '300px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none'
      }}></div>

      {/* Main Footer Content */}
      <div style={{
        maxWidth: '1400px',
        margin: '0 auto',
        padding: '4rem 2rem',
        position: 'relative',
        zIndex: 2
      }}>
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
          gap: '3rem',
          marginBottom: '3rem'
        }}>
          {/* Brand Section */}
          <div>
            <div style={{
              marginBottom: '1.5rem'
            }}>
                        <img 
                          src={logoImage}
                          alt="CMO VISTAMD Logo"
                          style={{
                            height: '100%',
                            width: 'auto',
                            maxWidth: '200px',
                            objectFit: 'contain',
                            objectPosition: 'left center'
                          }}
                        />
              <p style={{
                fontSize: '1.3rem',
                color: '#cbd5e1',
                lineHeight: '1.6',
                margin: 0
              }}>
                Excellence médicale et services de santé intégrés pour votre bien-être. 
              </p>
            </div>

            {/* Social Links */}
            <div style={{
              display: 'flex',
              gap: '1rem',
              marginTop: '1.5rem'
            }}>
              {socialLinks.map((social, index) => {
                const Icon = social.icon
                return (
                  <a
                    key={index}
                    href={social.href}
                    title={social.label}
                    style={{
                      width: '40px',
                      height: '40px',
                      background: 'rgba(34, 197, 94, 0.1)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      color: '#22c55e',
                      transition: 'all 0.3s ease',
                      cursor: 'pointer'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.background = 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)'
                      e.currentTarget.style.color = 'white'
                      e.currentTarget.style.transform = 'translateY(-3px)'
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.background = 'rgba(34, 197, 94, 0.1)'
                      e.currentTarget.style.color = '#22c55e'
                      e.currentTarget.style.transform = 'translateY(0)'
                    }}
                  >
                    <Icon size={18} />
                  </a>
                )
              })}
            </div>
          </div>

          {/* Services Links */}
          <div>
            <h4 style={{
              fontSize: '1.5rem',
              fontWeight: '600',
              color: '#ffffff',
              marginBottom: '1.5rem',
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem'
            }}>
              <span style={{ color: '#22c55e' }}>→</span>
              Services
            </h4>
            <ul style={{
              listStyle: 'none',
              padding: 0,
              margin: 0,
              display: 'flex',
              flexDirection: 'column',
              gap: '0.75rem'
            }}>
              {footerLinks.services.map((link, index) => (
                <li key={index}>
                  <a
                    href={link.href}
                    style={{
                      color: '#cbd5e1',
                      textDecoration: 'none',
                      transition: 'all 0.3s ease',
                      fontSize: '1.5rem'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.color = '#22c55e'
                      e.currentTarget.style.transform = 'translateX(5px)'
                      e.currentTarget.style.display = 'inline-block'
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.color = '#cbd5e1'
                      e.currentTarget.style.transform = 'translateX(0)'
                    }}
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Company Links */}
          <div>
            <h4 style={{
              fontSize: '1.5rem',
              fontWeight: '600',
              color: '#ffffff',
              marginBottom: '1.5rem',
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem'
            }}>
              <span style={{ color: '#22c55e' }}>→</span>
              Entreprise
            </h4>
            <ul style={{
              listStyle: 'none',
              padding: 0,
              margin: 0,
              display: 'flex',
              flexDirection: 'column',
              gap: '0.75rem'
            }}>
              {footerLinks.company.map((link, index) => (
                <li key={index}>
                  <a
                    href={link.href}
                    style={{
                      color: '#cbd5e1',
                      textDecoration: 'none',
                      transition: 'all 0.3s ease',
                      fontSize: '1.5rem'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.color = '#22c55e'
                      e.currentTarget.style.transform = 'translateX(5px)'
                      e.currentTarget.style.display = 'inline-block'
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.color = '#cbd5e1'
                      e.currentTarget.style.transform = 'translateX(0)'
                    }}
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h4 style={{
              fontSize: '1.5rem',
              fontWeight: '600',
              color: '#ffffff',
              marginBottom: '1.5rem',
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem'
            }}>
              <span style={{ color: '#22c55e' }}>→</span>
              Contact
            </h4>
            <div style={{
              display: 'flex',
              flexDirection: 'column',
              gap: '1rem'
            }}>
              {/* Phone */}
              <a
                href="tel:+2250700000000"
                style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: '0.75rem',
                  color: '#cbd5e1',
                  textDecoration: 'none',
                  transition: 'all 0.3s ease',
                  fontSize: '1.8rem'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.color = '#22c55e'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.color = '#cbd5e1'
                }}
              >
                <Phone size={18} style={{ color: '#22c55e', marginTop: '0.25rem', flexShrink: 0 }} />
                <span style={{ fontSize: '0.95rem' }}>+225 07 205 520 99 / +225 07 89 123 456</span>
              </a>

              {/* Email */}
              <a
                href="mailto:contact@cmovistamd.ci"
                style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: '0.75rem',
                  color: '#cbd5e1',
                  textDecoration: 'none',
                  fontSize: '1.8rem',
                  transition: 'all 0.3s ease'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.color = '#22c55e'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.color = '#cbd5e1'
                }}
              >
                <Mail size={18} style={{ color: '#22c55e', marginTop: '0.25rem', flexShrink: 0 }} />
                <span style={{ fontSize: '0.95rem' }}>contact@cmovistamd.com</span>
              </a>

              {/* Address */}
              <div
                style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: '0.75rem'
                }}
              >
                <MapPin size={18} style={{ color: '#22c55e', marginTop: '0.25rem', flexShrink: 0 }} />
                <span style={{ fontSize: '1rem', color: '#cbd5e1' }}>
                  Grand-Bassam, Abidjan 
                  Côte d'Ivoire
                </span>
              </div>

              {/* Emergency */}
              <div style={{
                padding: '0.75rem 1rem',
                background: 'rgba(34, 197, 94, 0.1)',
                border: '1px solid rgba(34, 197, 94, 0.3)',
                borderRadius: '0.5rem',
                color: '#C52222FF',
                fontSize: '1.5rem',
                fontWeight: '600',
                textAlign: 'center'
              }}>
                🚨 Urgence 24/7
              </div>
            </div>
          </div>
        </div>

        {/* Divider */}
        <div style={{
          height: '1px',
          background: 'linear-gradient(to right, transparent, rgba(34, 197, 94, 0.3), transparent)',
          marginBottom: '2rem'
        }}></div>

        {/* Bottom Footer */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
          gap: '2rem',
          alignItems: 'center',
          paddingTop: '2rem'
        }}>
          {/* Copyright */}
          <div style={{
            fontSize: '0.9rem',
            color: '#94a3b8',
            textAlign: 'center'
          }}>
            <p style={{ margin: 0 }}>
              © {currentYear} <span style={{ color: '#22c55e', fontWeight: 'bold' }}>CMO VISTAMD</span>. Tous droits réservés.
            </p>
          </div>

          {/* Legal Links */}
          <div style={{
            display: 'flex',
            justifyContent: 'center',
            flexWrap: 'wrap',
            gap: '1.5rem'
          }}>
            {footerLinks.legal.map((link, index) => (
              <a
                key={index}
                href={link.href}
                style={{
                  fontSize: '0.85rem',
                  color: '#94a3b8',
                  textDecoration: 'none',
                  transition: 'color 0.3s ease'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.color = '#22c55e'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.color = '#94a3b8'
                }}
              >
                {link.label}
              </a>
            ))}
          </div>

          {/* Made with love */}
          <div style={{
            fontSize: '0.9rem',
            color: '#94a3b8',
            textAlign: 'center',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            gap: '0.5rem'
          }}>
            Fait avec
            <Heart size={16} style={{ color: '#22c55e', fill: '#22c55e' }} />
            par Tchable
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          footer {
            padding: 2rem 1rem;
          }
        }
      `}</style>
    </footer>
  )
}

export default Footer