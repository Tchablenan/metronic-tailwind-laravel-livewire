// src/components/home/ContactSection.tsx
import { useState } from 'react'
import { Mail, Phone, MapPin, Clock, Send } from 'lucide-react'

const ContactSection = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
  })

  const [isSubmitting, setIsSubmitting] = useState(false)
  const [submitted, setSubmitted] = useState(false)

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setIsSubmitting(true)
    setTimeout(() => {
      setIsSubmitting(false)
      setSubmitted(true)
      setFormData({ name: '', email: '', phone: '', subject: '', message: '' })
      setTimeout(() => setSubmitted(false), 3000)
    }, 1000)
  }

  const locations = [
    {
      id: 1,
      name: 'Siège Principal',
      address: 'Grand-Bassam, Abidjan, Côte d\'Ivoire',
      phone:  ['+225 07 205 520 99 / ', '+225 07 89 123 456'],
      email: 'contact@cmovistamd.com',
      hours: 'Lun - Ven: 08:00 - 18:00',
      emergency: '24/7'
    },
   {/*  {
      id: 2,
      name: 'Service Urgence 24/7',
      address: 'Centre Médical - Cocody, Abidjan',
      phone: ['+225 07 205 520 99 / ', '+225 07 89 123 456'],
      email: 'urgence@cmovistamd.com',
      hours: 'Disponible 24h/24',
      emergency: '24/7 - Urgences'
    }*/}
  ]

  const contactInfo = [
    {
      icon: Phone,
      label: 'Téléphone',
      value: ['+225 07 205 520 99 / ', '+225 07 89 123 456'],
      link: ['tel:+225 07 205 520 99 ', 'tel:+225 07 89 123 456']
    },
    {
      icon: Mail,
      label: 'Email',
      value: 'contact@cmovistamd.com',
      link: 'mailto:contact@cmovistamd.com'
    },
    {
      icon: MapPin,
      label: 'Adresse',
      value: 'Grand-Bassam, Abidjan',
      link: '#'
    },
    {
      icon: Clock,
      label: 'Horaires',
      value: 'Lun - Ven: 08:00 - 18:00',
      link: '#'
    }
  ]

  return (
    <div id='contact' className="rts-contact-area rts-section-gap" style={{
      background: 'linear-gradient(180deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%)',
      padding: '100px 0',
      position: 'relative',
      overflow: 'hidden'
    }}>
      {/* Éléments de décoration avec fond coloré */}
      <div style={{
        position: 'absolute',
        top: '10%',
        left: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '10%',
        right: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.15) 0%, rgba(16, 117, 185, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
      }}></div>

      {/* SVG Phone - Top Left */}
      <svg 
        style={{
          position: 'absolute',
          top: '8%',
          left: '5%',
          width: '140px',
          height: '140px',
          opacity: 0.4,
          animation: 'float 9s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 12px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="30" y="15" width="40" height="70" rx="5" fill="#22c55e"/>
        <rect x="32" y="20" width="36" height="60" rx="2" fill="#10b981"/>
        <circle cx="50" cy="80" r="3" fill="white"/>
        <rect x="42" y="17" width="16" height="2" rx="1" fill="rgba(255,255,255,0.3)"/>
        <path d="M45,35 Q45,40 50,42 Q55,40 55,35" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round"/>
        <path d="M40,50 L60,50 M40,56 L60,56 M40,62 L55,62" stroke="white" strokeWidth="2" strokeLinecap="round"/>
      </svg>

      {/* SVG Email Envelope - Top Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '10%',
          right: '6%',
          width: '150px',
          height: '150px',
          opacity: 0.4,
          animation: 'float 10s ease-in-out infinite 1s',
          filter: 'drop-shadow(0 6px 14px rgba(16, 117, 185, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="20" y="30" width="60" height="40" rx="4" fill="#1075B9"/>
        <path d="M20,30 L50,55 L80,30" fill="#0ea5e9" stroke="#0c4a6e" strokeWidth="2"/>
        <path d="M20,70 L35,55 M80,70 L65,55" stroke="#0c4a6e" strokeWidth="2" strokeLinecap="round"/>
        <circle cx="75" cy="35" r="3" fill="#fbbf24"/>
      </svg>

      {/* SVG Location Pin - Bottom Right */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '12%',
          right: '8%',
          width: '130px',
          height: '130px',
          opacity: 0.35,
          animation: 'bounce 3s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 14px rgba(239, 68, 68, 0.3))'
        }}
        viewBox="0 0 100 100"
      >
        <path d="M50,20 Q35,20 25,35 Q25,50 50,80 Q75,50 75,35 Q65,20 50,20 Z" fill="#ef4444"/>
        <circle cx="50" cy="40" r="12" fill="white"/>
        <circle cx="50" cy="40" r="6" fill="#ef4444"/>
      </svg>

      {/* SVG Message Bubble - Bottom Left */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '15%',
          left: '6%',
          width: '140px',
          height: '140px',
          opacity: 0.4,
          animation: 'float 8s ease-in-out infinite 2s',
          filter: 'drop-shadow(0 6px 14px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="20" y="25" width="60" height="45" rx="8" fill="#22c55e"/>
        <path d="M35,70 L40,80 L45,70 Z" fill="#22c55e"/>
        <line x1="30" y1="40" x2="70" y2="40" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="30" y1="50" x2="65" y2="50" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="30" y1="60" x2="60" y2="60" stroke="white" strokeWidth="2.5" strokeLinecap="round"/>
      </svg>

      {/* SVG Clock 24/7 - Middle Right */}
      <svg 
        style={{
          position: 'absolute',
          top: '40%',
          right: '3%',
          width: '110px',
          height: '110px',
          opacity: 0.35,
          animation: 'rotate 20s linear infinite',
          filter: 'drop-shadow(0 4px 10px rgba(251, 191, 36, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="50" r="35" fill="#fbbf24" stroke="#f59e0b" strokeWidth="3"/>
        <circle cx="50" cy="50" r="3" fill="white"/>
        <line x1="50" y1="50" x2="50" y2="30" stroke="white" strokeWidth="3" strokeLinecap="round"/>
        <line x1="50" y1="50" x2="65" y2="50" stroke="white" strokeWidth="2.5" strokeLinecap="round"/>
        <text x="50" y="72" fontSize="12" fill="white" fontWeight="bold" textAnchor="middle">24/7</text>
      </svg>

      {/* Particules décoratives */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        pointerEvents: 'none',
        zIndex: 1
      }}>
        {[...Array(10)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: i % 3 === 0 ? '7px' : '4px',
              height: i % 3 === 0 ? '7px' : '4px',
              background: i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#ef4444',
              borderRadius: '50%',
              top: `${15 + Math.random() * 70}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.5,
              boxShadow: `0 0 8px ${i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#ef4444'}`,
              animation: `particleFloat ${8 + Math.random() * 4}s ease-in-out infinite ${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      <div className="container" style={{ position: 'relative', zIndex: 2 }}>
        {/* Header */}
        <div style={{
          textAlign: 'center',
          marginBottom: '4rem',
          maxWidth: '800px',
          margin: '0 auto 4rem'
        }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            background: 'rgba(34, 197, 94, 0.15)',
            padding: '0.6rem 1.2rem',
            borderRadius: '2rem',
            marginBottom: '1rem',
            boxShadow: '0 4px 12px rgba(34, 197, 94, 0.2)',
            animation: 'slideInDown 0.6s ease-out 0.2s both'
          }}>
            <span style={{
              color: '#22c55e',
              fontWeight: '700',
              fontSize: '0.9rem',
              textTransform: 'uppercase',
              letterSpacing: '0.1em'
            }}>
              Nous Contacter
            </span>
          </div>

          <h2 style={{
            fontSize: 'clamp(2rem, 5vw, 3rem)',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.2',
            marginBottom: '1.5rem',
            animation: 'slideInDown 0.6s ease-out 0.3s both'
          }}>
            Restez en Contact avec Nous
          </h2>

          <p style={{
            fontSize: 'clamp(1rem, 2vw, 1.15rem)',
            color: '#64748b',
            lineHeight: '1.8',
            animation: 'slideInDown 0.6s ease-out 0.4s both',
            padding: '0 1rem'
          }}>
            Nous serions heureux de vous aider. Contactez-nous par téléphone, email ou remplissez le formulaire ci-dessous
          </p>
        </div>

        {/* Contact Info Cards - RESPONSIVE */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))',
          gap: '1.5rem',
          marginBottom: '4rem',
          animation: 'slideInUp 0.6s ease-out 0.5s both'
        }}>
          {contactInfo.map((info, index) => {
              const Icon = info.icon
              const href = Array.isArray(info.link) ? info.link[0] : info.link
              return (
                <a
                  key={index}
                  href={href}
                  style={{
                    padding: '1.75rem 1.5rem',
                    background: '#ffffff',
                    border: '2px solid rgba(34, 197, 94, 0.15)',
                    borderRadius: '1rem',
                    textDecoration: 'none',
                    transition: 'all 0.3s ease',
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                    gap: '1rem',
                    textAlign: 'center',
                    cursor: 'pointer',
                    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.05)'
                  }}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.boxShadow = '0 15px 40px rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.transform = 'translateY(-5px)'
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.15)'
                    e.currentTarget.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)'
                    e.currentTarget.style.transform = 'translateY(0)'
                  }}
                >
                  <div style={{
                    width: '60px',
                    height: '60px',
                    background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                    borderRadius: '0.75rem',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    color: 'white',
                    boxShadow: '0 4px 12px rgba(34, 197, 94, 0.3)'
                  }}>
                    <Icon size={28} />
                  </div>
                  <div style={{ width: '100%' }}>
                    <h4 style={{
                      fontSize: '1rem',
                      fontWeight: '600',
                      color: '#0f172a',
                      marginBottom: '0.5rem'
                    }}>
                      {info.label}
                    </h4>
                    <p style={{
                      fontSize: 'clamp(0.85rem, 2vw, 0.9rem)',
                      color: '#64748b',
                      margin: 0,
                      wordBreak: 'break-word',
                      overflowWrap: 'break-word',
                      hyphens: 'auto'
                    }}>
                      {info.value}
                    </p>
                  </div>
                </a>
              )
            })}
        </div>

        {/* Main Content - Form + Locations - RESPONSIVE */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 450px), 1fr))',
          gap: '3rem',
          marginBottom: '4rem',
          alignItems: 'start'
        }}>
          {/* Contact Form */}
          <div style={{
            animation: 'slideInLeft 0.6s ease-out 0.6s both'
          }}>
            <div style={{
              padding: 'clamp(1.5rem, 3vw, 2.5rem)',
              background: '#ffffff',
              border: '2px solid rgba(34, 197, 94, 0.15)',
              borderRadius: '1.5rem',
              boxShadow: '0 20px 60px rgba(0, 0, 0, 0.08)'
            }}>
              <h3 style={{
                fontSize: 'clamp(1.25rem, 3vw, 1.5rem)',
                fontWeight: 'bold',
                color: '#0f172a',
                marginBottom: '2rem'
              }}>
                Envoyez-nous un Message
              </h3>

              <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                <input
                  type="text"
                  name="name"
                  placeholder="Votre Nom"
                  value={formData.name}
                  onChange={handleChange}
                  required
                  style={{
                    width: '100%',
                    padding: '0.875rem 1.25rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    border: '1.5px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    color: '#0f172a',
                    fontSize: '1rem',
                    transition: 'all 0.3s ease',
                    boxSizing: 'border-box'
                  }}
                  onFocus={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.12)'
                    e.currentTarget.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)'
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                />

                <input
                  type="email"
                  name="email"
                  placeholder="Votre Email"
                  value={formData.email}
                  onChange={handleChange}
                  required
                  style={{
                    width: '100%',
                    padding: '0.875rem 1.25rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    border: '1.5px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    color: '#0f172a',
                    fontSize: '1rem',
                    transition: 'all 0.3s ease',
                    boxSizing: 'border-box'
                  }}
                  onFocus={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.12)'
                    e.currentTarget.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)'
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                />

                <input
                  type="tel"
                  name="phone"
                  placeholder="Votre Téléphone"
                  value={formData.phone}
                  onChange={handleChange}
                  style={{
                    width: '100%',
                    padding: '0.875rem 1.25rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    border: '1.5px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    color: '#0f172a',
                    fontSize: '1rem',
                    transition: 'all 0.3s ease',
                    boxSizing: 'border-box'
                  }}
                  onFocus={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.12)'
                    e.currentTarget.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)'
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                />

                <input
                  type="text"
                  name="subject"
                  placeholder="Sujet"
                  value={formData.subject}
                  onChange={handleChange}
                  required
                  style={{
                    width: '100%',
                    padding: '0.875rem 1.25rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    border: '1.5px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    color: '#0f172a',
                    fontSize: '1rem',
                    transition: 'all 0.3s ease',
                    boxSizing: 'border-box'
                  }}
                  onFocus={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.12)'
                    e.currentTarget.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)'
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                />

                <textarea
                  name="message"
                  placeholder="Votre Message"
                  value={formData.message}
                  onChange={handleChange}
                  required
                  rows={5}
                  style={{
                    width: '100%',
                    padding: '0.875rem 1.25rem',
                    background: 'rgba(34, 197, 94, 0.08)',
                    border: '1.5px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    color: '#0f172a',
                    fontSize: '1rem',
                    transition: 'all 0.3s ease',
                    resize: 'vertical',
                    fontFamily: 'inherit',
                    boxSizing: 'border-box'
                  }}
                  onFocus={(e) => {
                    e.currentTarget.style.borderColor = '#22c55e'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.12)'
                    e.currentTarget.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)'
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.08)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                />

                <button
                  type="submit"
                  disabled={isSubmitting}
                  style={{
                    padding: '1rem',
                    background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                    color: 'white',
                    fontWeight: 'bold',
                    border: 'none',
                    borderRadius: '0.75rem',
                    fontSize: '1.05rem',
                    cursor: isSubmitting ? 'not-allowed' : 'pointer',
                    transition: 'all 0.3s ease',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '0.5rem',
                    opacity: isSubmitting ? 0.7 : 1,
                    boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)'
                  }}
                  onMouseEnter={(e) => {
                    if (!isSubmitting) {
                      e.currentTarget.style.transform = 'translateY(-3px)'
                      e.currentTarget.style.boxShadow = '0 15px 45px rgba(34, 197, 94, 0.5)'
                    }
                  }}
                  onMouseLeave={(e) => {
                    if (!isSubmitting) {
                      e.currentTarget.style.transform = 'translateY(0)'
                      e.currentTarget.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.3)'
                    }
                  }}
                >
                  {isSubmitting ? (
                    <>
                      <span>⏳</span>
                      Envoi...
                    </>
                  ) : submitted ? (
                    <>
                      <span>✓</span>
                      Message Envoyé !
                    </>
                  ) : (
                    <>
                      <Send size={18} />
                      Envoyer le Message
                    </>
                  )}
                </button>
              </form>
            </div>
          </div>

          {/* Locations Info */}
          <div style={{
            display: 'flex',
            flexDirection: 'column',
            gap: '1.5rem',
            animation: 'slideInRight 0.6s ease-out 0.6s both'
          }}>
            <h3 style={{
              fontSize: 'clamp(1.25rem, 3vw, 1.5rem)',
              fontWeight: 'bold',
              color: '#0f172a',
              marginBottom: '0.5rem'
            }}>
              Nos Localités
            </h3>

            {locations.map((location, index) => (
              <div
                key={location.id}
                style={{
                  padding: 'clamp(1.5rem, 3vw, 2rem)',
                  background: '#ffffff',
                  border: '2px solid rgba(34, 197, 94, 0.15)',
                  borderRadius: '1rem',
                  transition: 'all 0.3s ease',
                  animation: `slideInUp 0.6s ease-out ${0.7 + index * 0.1}s both`,
                  boxShadow: '0 4px 12px rgba(0, 0, 0, 0.05)'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.borderColor = '#22c55e'
                  e.currentTarget.style.boxShadow = '0 15px 40px rgba(34, 197, 94, 0.2)'
                  e.currentTarget.style.transform = 'translateY(-5px)'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.15)'
                  e.currentTarget.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)'
                  e.currentTarget.style.transform = 'translateY(0)'
                }}
              >
                <div style={{
                  display: 'flex',
                  alignItems: 'flex-start',
                  gap: '1rem',
                  marginBottom: '1rem'
                }}>
                  <MapPin size={24} style={{ color: '#22c55e', marginTop: '0.25rem', flexShrink: 0 }} />
                  <div>
                    <h4 style={{
                      fontSize: 'clamp(1rem, 2.5vw, 1.15rem)',
                      fontWeight: 'bold',
                      color: '#0f172a',
                      marginBottom: '0.25rem'
                    }}>
                      {location.name}
                    </h4>
                    <p style={{
                      fontSize: 'clamp(0.85rem, 2vw, 0.95rem)',
                      color: '#64748b',
                      margin: 0
                    }}>
                      {location.address}
                    </p>
                  </div>
                </div>

               {/*  */} <div style={{
                  display: 'flex',
                  flexDirection: 'column',
                  gap: '0.75rem',
                  paddingTop: '1rem',
                  borderTop: '1px solid rgba(34, 197, 94, 0.15)'
                }}>
                  {/* Téléphone - SANS <a> TAG */}
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'flex-start', 
                    gap: '0.75rem',
                    width: '100%',
                    minHeight: '30px'
                  }}>
                    <Phone size={16} style={{ color: '#22c55e', flexShrink: 0, marginTop: '0.25rem' }} />
                    <div 
                      onClick={() => {
                        if (location.phone) {
                          const phoneStr = Array.isArray(location.phone) ? location.phone[0] : location.phone;
                          window.location.href = `tel:${phoneStr.replace(/\s/g, '')}`;
                        }
                      }}
                      style={{
                        flex: 1,
                        cursor: 'pointer'
                      }}
                    >
                      <span style={{
                        color: '#0f172a',
                        fontSize: '14px',
                        fontWeight: '600',
                        display: 'block',
                        textDecoration: 'underline',
                        textDecorationColor: '#22c55e',
                        textDecorationThickness: '2px',
                        textUnderlineOffset: '2px'
                      }}>
                        {location.phone}
                      </span>
                    </div>
                  </div>

                  {/* Email - SANS <a> TAG */}
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'flex-start', 
                    gap: '0.75rem',
                    width: '100%',
                    minHeight: '30px'
                  }}>
                    <Mail size={16} style={{ color: '#22c55e', flexShrink: 0, marginTop: '0.25rem' }} />
                    <div 
                      onClick={() => {
                        window.location.href = `mailto:${location.email}`
                      }}
                      style={{
                        flex: 1,
                        cursor: 'pointer'
                      }}
                    >
                      <span style={{
                        color: '#0f172a',
                        fontSize: '14px',
                        fontWeight: '600',
                        display: 'block',
                        wordBreak: 'break-word',
                        textDecoration: 'underline',
                        textDecorationColor: '#22c55e',
                        textDecorationThickness: '2px',
                        textUnderlineOffset: '2px'
                      }}>
                        {location.email}
                      </span>
                    </div>
                  </div>

                  {/* Horaires */}
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'flex-start', 
                    gap: '0.75rem',
                    width: '100%',
                    minHeight: '30px'
                  }}>
                    <Clock size={16} style={{ color: '#22c55e', flexShrink: 0, marginTop: '0.25rem' }} />
                    <span style={{
                      color: '#64748b',
                      fontSize: '14px',
                      fontWeight: '500',
                      display: 'block',
                      flex: 1
                    }}>
                      {location.hours}
                    </span>
                  </div>

                  {/* Badge Emergency */}
                  <div style={{
                    padding: '0.75rem 1rem',
                    background: 'rgba(34, 197, 94, 0.15)',
                    borderRadius: '0.5rem',
                    color: '#22c55e',
                    fontWeight: '700',
                    fontSize: '14px',
                    textAlign: 'center',
                    boxShadow: '0 2px 8px rgba(34, 197, 94, 0.15)',
                    marginTop: '0.5rem',
                    position: 'relative',
                    zIndex: 10
                  }}>
                    {location.emergency || '24/7'}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <style>{`
        @keyframes slideInDown {
          from {
            opacity: 0;
            transform: translateY(-30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes slideInUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes slideInLeft {
          from {
            opacity: 0;
            transform: translateX(-50px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }

        @keyframes slideInRight {
          from {
            opacity: 0;
            transform: translateX(50px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(30px);
          }
        }

        @keyframes bounce {
          0%, 100% {
            transform: translateY(0);
          }
          50% {
            transform: translateY(-15px);
          }
        }

        @keyframes rotate {
          from {
            transform: rotate(0deg);
          }
          to {
            transform: rotate(360deg);
          }
        }

        @keyframes particleFloat {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.5;
          }
          25% {
            opacity: 0.8;
          }
          50% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.5;
          }
          75% {
            opacity: 0.8;
          }
        }

        /* CRITICAL FIX FOR PHONE NUMBERS - MOBILE SPECIFIC */
        .location-phone-link,
        .location-email-link {
          color: #0f172a !important;
          text-decoration: none !important;
          display: block !important;
          width: 100% !important;
          max-width: 100% !important;
          word-wrap: break-word !important;
          word-break: break-word !important;
          overflow-wrap: break-word !important;
          white-space: normal !important;
          line-height: 1.6 !important;
          min-height: 20px !important;
        }

        .location-phone-link span,
        .location-email-link span {
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
        }

        .location-hours-text {
          color: #64748b !important;
          font-size: 14px !important;
          display: inline-block !important;
          width: auto !important;
          font-weight: 500 !important;
          line-height: 1.6 !important;
          background-color: transparent !important;
          opacity: 1 !important;
          visibility: visible !important;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
          .rts-contact-area .container > div:first-child {
            margin-bottom: 3rem !important;
          }
          
          /* FORCE VISIBILITY ON MOBILE */
          .location-phone-link,
          .location-email-link {
            font-size: 13px !important;
            min-width: 0 !important;
            flex: 1 !important;
          }
          
          .location-phone-link span,
          .location-email-link span {
            font-size: 13px !important;
            word-break: break-all !important;
          }
        }

        @media (max-width: 640px) {
          /* Ensure phone numbers and emails wrap properly on small screens */
          a[href^="tel:"],
          a[href^="mailto:"],
          .location-phone-link,
          .location-email-link {
            word-break: break-all !important;
            overflow-wrap: break-word !important;
            font-size: 13px !important;
            display: block !important;
          }
          
          .location-phone-link span,
          .location-email-link span {
            display: block !important;
            word-break: break-all !important;
          }
        }
      `}</style>
    </div>
  )
}

export default ContactSection