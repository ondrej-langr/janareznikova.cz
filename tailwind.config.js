/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./{resources,app}/**/*.{php,js,ts,tsx,css,scss,blade.php}'],
  theme: {
    extend: {
      colors: {
        site: {
          blue: '#0c1654',
          gray: '#6E6E6E',
          tonic: '#AAB2E6',
          text: { white: '#fcfafd', black: '#151f16' },
        },
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '1rem',
          md: '2rem',
          lg: '2rem',
          xl: '2rem',
          '2xl': '2rem',
        },
        screens: {
          sm: '600px',
          md: '728px',
          lg: '984px',
          xl: '1090px',
          '2xl': '1090px',
        },
      },
      lineHeight: {
        loose: '1.85',
        'extra-loose': '2.1',
      },
    },
    fontFamily: {
      sans: ['Poppins', 'Arial', 'sans-serif'],
    },
  },
  safelist: [/widget.*/],
  plugins: [
    {
      handler({ addComponents, addBase, theme }) {
        const sectionPaddingDesktopSize = theme('spacing.32');
        const sectionPaddingSize = theme('spacing.16');

        addBase({
          body: {
            fontSize: '1.125rem',
            fontWeight: 300,
          },
          'strong, b': {
            fontWeight: 600,
          },
          'h2,h3,h4,h5,h6': {
            fontWeight: 600,
          },
          dialog: {
            '&[open]': {
              display: 'flex',
            },
            maxHeight: 'unset',
            maxWidth: 'unset',
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            alignItems: 'center',
            backgroundColor: 'transparent',
          },
        });

        addComponents({
          '.site-header': {
            padding: '5px 0px',
            position: 'fixed',
            top: 'calc(0 + var(--wp-admin--admin-bar--height))',
            left: 0,
            width: '100%',
            zIndex: 20,
            '.custom-logo': {
              width: 180,
              marginTop: '10px',
              height: 'auto',
              position: 'relative',

              '@media (min-width: 690px)': {
                marginTop: 0,
                width: 300,
              },
            },
            nav: {
              display: 'none',
              justifyContent: 'center',
              alignItems: 'center',
              position: 'fixed',
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              backgroundColor: 'white',

              '&[data-open="true"]': {
                display: 'flex',
              },

              '.menu-hlavni-menu-container': {
                ul: {
                  display: 'flex',
                  textTransform: 'uppercase',
                  gap: '30px',
                  fontSize: '1.8rem',
                  flexFlow: 'column',
                  textAlign: 'center',

                  a: {
                    '&:hover': {
                      textDecoration: 'underline',
                    },
                  },

                  '@media (min-width: 768px)': {
                    flexFlow: 'row',
                    textAlign: 'right',
                    fontSize: '1.1rem',
                  },
                },
              },

              '@media (min-width: 768px)': {
                position: 'relative',
                display: 'flex',
                width: 'auto',
                justifyContent: 'space-between',
                backgroundColor: 'transparent',
              },
            },

            '@media (min-width: 690px)': {
              padding: '20px 0px',
            },
          },
          '.footer': {
            padding: '80px 0',
            '.widget.widget_block': {
              color: 'white',

              h2: {
                fontWeight: 'semibold',
                fontSize: '1.5rem',
                textTransform: 'uppercase',
                marginBottom: '25px',
              },

              p: {
                color: '#808ebd',
              },

              strong: {
                color: 'white',
              },

              a: {
                '&:hover': {
                  textDecoration: 'underline',
                },
              },
            },
            '@media (min-width: 690px)': {
              padding: '120px 0',
            },
          },
          '.section': {
            padding: `${sectionPaddingSize} 0`,

            '&.dark': {
              backgroundColor: theme('colors.site.blue'),
            },

            '@media (min-width: 690px)': {
              padding: `${sectionPaddingDesktopSize} 0`,
            },
          },
          '.typography': {
            'h1,h2': {
              fontSize: '1.8rem',
              marginBottom: '1.5rem',
              '@media (min-width: 690px)': {
                fontSize: '2.375rem',
              },
            },
            'h3,h4,h5': {
              fontSize: '1.2rem',
              marginBottom: '1rem',
              '@media (min-width: 690px)': {
                fontSize: '1.8rem',
              },
            },
            a: {
              fontWeight: 'bold',
              color: theme('colors.white'),
              '&:hover': {
                textDecoration: 'underline',
              },
            },
            ul: {
              listStyle: 'disc',
              paddingLeft: '1rem',
            },
            ol: {
              listStyle: 'decimal',
              paddingLeft: '1.6rem',
            },
            'p, li': {
              fontSize: '18px',
              lineHeight: 1.85,
            },
            '&:not(.dark)': {
              'p, li': {
                color: theme('colors.site.gray'),
              },
            },
            '&.dark': {
              'p, li': {
                color: theme('colors.site.tonic'),
              },
            },
          },
          '.keen-slider': {
            '&__slide': {
              transition: 'padding 200ms ease-in, opacity 200ms ease-in,',
              '&:not(.active)': {
                padding: '2.5rem 1.4rem',
                opacity: 0.6,
              },
            },
          },
        });
      },
    },
  ],
};
